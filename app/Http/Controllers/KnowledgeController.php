<?php

namespace App\Http\Controllers;

use App\Http\Requests\KnowledgeCreateRequest;
use App\Models\Cohort;
use App\Models\CohortsBilans;
use App\Models\Question;
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
  - Si `{$nbr_response} = 2`, il y aura seulement deux réponses : "answer_0" et "answer_1", et les réponses "answer_2" et "answer_3" seront à `null`.
  - Si `{$nbr_response} = 3`, il y aura trois réponses : "answer_0", "answer_1", et "answer_2". La réponse "answer_3" sera à `null`.
  - Si `{$nbr_response} = 4`, toutes les réponses ("answer_0", "answer_1", "answer_2", "answer_3") seront remplies.
- Les questions doivent être **variées et uniques**, même si la même demande est répétée avec le même langage. Ne réutilise pas les mêmes formulations ou exemples si le langage "{$langage}" est redemandé ultérieurement.
- Si {$number} = 1, alors la seule question doit être de niveau "débutant". Il ne doit y avoir **aucune** question de niveau "intermédiaire" ou "avancé".
- Répartis les questions par difficulté de manière suivante : 30% de questions "débutant", 40% "intermédiaire", et 30% "avancé".
- Le champ "correct_answer" doit contenir directement le **contenu de la bonne réponse** (au lieu de la clé "answer_0", "answer_1", etc.).
- Chaque objet JSON doit également contenir une clé "link" de type string, qui contient l'URL d'une image trouvée sur Internet représentant visuellement le langage demandé (ex : logo, icône ou image officielle).

### Format JSON attendu :
Réponds uniquement avec un tableau JSON **brut** (sans texte explicatif, sans balise markdown).
Chaque élément du tableau doit être un objet contenant :
- "question" : énoncé de la question
- "level" : "débutant", "intermédiaire" ou "avancé"
- "answer_0", "answer_1", "answer_2", "answer_3" : réponses proposées (certaines peuvent être `null` si {$nbr_response} est inférieur à 4)
- "correct_answer" : le contenu de la bonne réponse (ex. : "Une liste est mutable, un tuple est immutable.")
- "link" : une URL valide pointant vers une image du langage {$langage}

### Exemple :
[
  {
    "question": "Quelle est la différence entre une liste et un tuple en Python ?",
    "level": "intermédiaire",
    "answer_0": "Une liste est mutable, un tuple est immutable.",
    "answer_1": "Un tuple est mutable, une liste est immutable.",
    "answer_2": "Il n'y a pas de différence.",
    "answer_3": null,
    "correct_answer": "Une liste est mutable, un tuple est immutable.",
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
