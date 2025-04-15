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
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\Http;


class KnowledgeController extends Controller
{
    /**
     * Display the page of knowledge
     * The student can see the QCMs of his cohort
     * The admin and teacher can see all QCMs
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

        return view('pages.knowledge.index', compact('qcm', 'cohort', 'user'));
    }


    /**
     * Connect to the Gemini API
     * Generate a QCM in JSON format
     * The QCM is generated based on the language and the number of questions and answers
     * Create a new QCM in the database
     * Create the questions in the database
     *
     * @param KnowledgeCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws ConnectionException
     */
    public function createQcm(KnowledgeCreateRequest $request)
    {
        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . config('services.gemini.api_key');

        $langage = $request->input('langage');
        $number = $request->input('number');
        $nbr_response = $request->input('response');

        $prompt = <<<EOT
Tu es un professeur expert en informatique qui souhaite générer un questionnaire à choix multiples (QCM) pour ses élèves sur le langage de programmation suivant : "{$langage}".

### 🔍 Vérification préalable :
Avant de générer quoi que ce soit, vérifie si "{$langage}" est **un langage de programmation reconnu et réellement existant** (par exemple : Python, JavaScript, Java, C++, Go, Rust, PHP, etc.).
Si ce n'est **pas** un langage connu ou s'il n'existe **pas réellement**, réponds **strictement** par la valeur : `null`.

---

### ✅ Objectif :
Créer un tableau JSON contenant exactement {$number} questions sur "{$langage}", en respectant le nombre de réponses par question défini par : `{$nbr_response}`.

### 📊 Répartition des difficultés :
- Si {$number} = 1, la seule question doit être de niveau **"débutant"**.
- Sinon, répartis comme suit :
  - 30% de questions **débutant**
  - 40% de questions **intermédiaire**
  - 30% de questions **avancé**

### 🧠 Réponses attendues :
- Chaque question contient entre 2 et 4 réponses selon la valeur de `{$nbr_response}` :
  - Si `{$nbr_response} = 2` → utilise uniquement `"answer_0"` et `"answer_1"` ; mets `"answer_2"` et `"answer_3"` à `null`.
  - Si `{$nbr_response} = 3` → remplis `"answer_0"` à `"answer_2"`, mets `"answer_3"` à `null`.
  - Si `{$nbr_response} = 4` → remplis `"answer_0"` à `"answer_3"`.
- Le champ `"correct_answer"` doit contenir **uniquement une des clés suivantes** : `"answer_0"`, `"answer_1"`, `"answer_2"` ou `"answer_3"`.
- **Important :** Ne mets **presque jamais** la même clé dans `"correct_answer"` pour toutes les questions. La bonne réponse ne doit **pas être toujours `"answer_0"`** par exemple. Il doit y avoir **de la variété**.

---

### 🖼️ Image :
- Chaque question doit aussi contenir une **clé `"link"`** avec l’URL d’une **image réellement accessible** en rapport avec le langage demandé.
- Cette image doit :
  - Être en ligne et **retourner un code HTTP 200**,
  - **Ne pas** rediriger vers une page dont le titre contient **"Page Not Found"**, **"404"**, ou un message d’erreur,
  - Être une **image pertinente** liée au langage (logo officiel, illustration représentative, etc.).

---

### 📦 Format attendu :
Réponds uniquement avec un **tableau JSON brut**. Aucune balise `json`, pas de texte supplémentaire.

Chaque objet JSON du tableau doit inclure :
- `"question"` : l'énoncé de la question
- `"level"` : "débutant", "intermédiaire" ou "avancé"
- `"answer_0"`, `"answer_1"`, `"answer_2"`, `"answer_3"` : les réponses (certaines peuvent être `null`)
- `"correct_answer"` : la **clé exacte** correspondant à la bonne réponse (ex : `"answer_1"`)
- `"link"` : une **URL d’image valide**, vérifiée, et liée au langage

---

### 🧪 Exemple de résultat :
```json
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
EOT;


        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($url, [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ]);
        $text = $response->json('candidates.0.content.parts.0.text');

        // Remove the "```json" and "```" from the beginning and end of the response
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

    /**
     * Edit the cohort of a QCM
     * @param Request $request
     * @param $id
     * @return Factory|View|Application|object
     */
    public function updateQcmCohort(Request $request, $id)
    {
        $id = decrypt($id);
        $qcm = CohortsBilans::findOrFail($id);
        $qcm->cohort_id = $request->input('select');
        $qcm->save();
        return redirect()->route('knowledge.index');


    }


}
