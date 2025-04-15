<?php

namespace App\Http\Controllers;

use App\Http\Requests\KnowledgeCreateRequest;
use App\Models\Cohort;
use App\Models\CohortsBilans;
use App\Models\Question;
use App\Models\UserBilans;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\Http;


class KnowledgeController extends Controller
{
    /**
     * Display the page
     *
     * @return Factory|View|Application|object
     */
    public function index() {

        $cohort = Cohort::all();
        $user = auth()->user();





        if ($user->school()->pivot->role == 'student' && $user->cohort_id != null)
        {
            $qcm = CohortsBilans::where('cohort_id', $user->cohort_id)->get();
        }
        elseif ($user->school()->pivot->role == 'admin' || $user->school()->pivot->role == 'teacher')
        {
            $qcm = CohortsBilans::all();
        }
        else
        {
            $qcm = [];
        }


        return view('pages.knowledge.index', compact('qcm', 'cohort'));
    }






    /**
     * Create a new QCM
     *
     * @param KnowledgeCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createQcm(KnowledgeCreateRequest $request)
    {
        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . config('services.gemini.api_key');

        $langage = $request->input('langage');
        $number = $request->input('number');
        $nbr_response = $request->input('response');

        $prompt = <<<EOT
Tu es un professeur expert en informatique qui souhaite générer un questionnaire à choix multiples (QCM) pour ses élèves sur le langage {$langage}. Tu dois créer un tableau JSON contenant exactement {$number} questions, classées par niveau de difficulté : "débutant", "intermédiaire", et "avancé", avec une répartition de 30% de questions simples, 40% de questions moyennes et 30% de questions difficiles.

### Contraintes :
- Si le langage "{$langage}" n'est pas un langage de programmation reconnu ou n'existe pas, réponds uniquement avec la valeur `null`.
- Le nombre de réponses par question est défini par la variable `{$nbr_response}` :
  - Si `{$nbr_response} = 2`, seules "answer_0" et "answer_1" seront remplies. "answer_2" et "answer_3" devront être à `null`.
  - Si `{$nbr_response} = 3`, "answer_0" à "answer_2" seront remplies. "answer_3" devra être à `null`.
  - Si `{$nbr_response} = 4`, alors "answer_0" à "answer_3" doivent toutes être remplies.
- Les questions doivent être **variées et uniques**, même si le même langage est demandé plusieurs fois. Ne réutilise jamais la même question ni les mêmes formulations.
- Si {$number} = 1, la question doit être uniquement de niveau "débutant".
- Répartis les questions par difficulté comme suit : 30% "débutant", 40% "intermédiaire", 30% "avancé".
- Le champ **"correct_answer"** doit contenir la **clé exacte** de la bonne réponse : `"answer_0"`, `"answer_1"`, `"answer_2"` ou `"answer_3"` (selon le nombre de réponses).
- **La clé de la bonne réponse ("correct_answer") ne doit pas toujours être la même.** Évite qu’elle soit identique d'une question à l'autre. Par exemple, il ne faut **presque jamais** avoir `"correct_answer": "answer_0"` plusieurs fois de suite.
- Chaque question doit inclure une clé `"link"` contenant une **URL d’image valide** (logo, icône, etc.) du langage demandé. Cette URL doit :
  - Pointer vers une **image réellement accessible** (code HTTP 200),
  - Ne **pas rediriger vers une page avec un titre comme "Page Not Found"** (ou "Not Found", "404", etc.),
  - Montrer un **logo ou une illustration pertinente** liée au langage {$langage}.

### Format JSON attendu :
Réponds uniquement avec un tableau JSON **brut** (aucun texte explicatif, aucune balise markdown).
Chaque objet du tableau doit contenir :
- "question" : énoncé de la question
- "level" : "débutant", "intermédiaire" ou "avancé"
- "answer_0", "answer_1", "answer_2", "answer_3" : réponses proposées (certaines peuvent être `null` selon {$nbr_response})
- "correct_answer" : clé de la bonne réponse (ex. : `"answer_1"`, `"answer_2"`, etc.)
- "link" : URL d’une image en ligne valide (retourne HTTP 200 et **n'affiche pas une page dont le titre est "Page Not Found"**)

### Exemple :
[
  {
    "question": "Quelle est la différence entre une liste et un tuple en Python ?",
    "level": "intermédiaire",
    "answer_0": "Une liste est mutable, un tuple est immutable.",
    "answer_1": "Un tuple est mutable, une liste est immutable.",
    "answer_2": "Il n'y a pas de différence.",
    "answer_3": null,
    "correct_answer": "answer_0",
    "link": "https://www.python.org/static/community_logos/python-logo-generic.svg"
  }
]

Génère maintenant {$number} question(s) selon ces consignes.
EOT;



        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($url, [
            'contents' => [
                [
                    //add prompt in the request
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ]);
        $text = $response->json('candidates.0.content.parts.0.text');

        $cleanJson = preg_replace('/^```json\s*/', '', $text);
        $cleanJson = preg_replace('/\s*```$/', '', $cleanJson);

        $questions_json = json_decode($cleanJson, true);
        if ($questions_json === null) {
            return redirect()->route('knowledge.index')->with('error', 'Le langage demandé n\'est pas reconnu.');
        }
        $qcm = new CohortsBilans();
        $qcm->name = $langage;
        $qcm->link = $questions_json[0]['link'];
        $qcm->save();
        for ($i = 0; $i < sizeof($questions_json); $i++) {
            $questions = new Question();
            $questions->bilans_id = $qcm->id;
            $questions->question = $questions_json[$i]['question'];
            $questions->level = $questions_json[$i]['level'];
            $questions->answer_0 = $questions_json[$i]['answer_0'];
            $questions->answer_1 = $questions_json[$i]['answer_1'];
            $questions->answer_2 = $questions_json[$i]['answer_2'];
            $questions->answer_3 = $questions_json[$i]['answer_3'];
            $questions->correct_answer = $questions_json[$i]['correct_answer'];
            $questions->save();

        }


        return redirect()->route('knowledge.index');
    }


    public function updateQcmCohort(Request $request, $id)
    {
        $id = decrypt($id);
        $qcm = CohortsBilans::findOrFail($id);
        $qcm->cohort_id = $request->input('select');
        $qcm->save();
        return redirect()->route('knowledge.index');


    }


}
