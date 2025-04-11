<?php

namespace App\Http\Controllers;

use App\Http\Requests\KnowledgeCreateRequest;
use App\Models\Qcm;
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

        $qcm = Qcm::all();
        $questions = Question::all();


        return view('pages.knowledge.index', compact('qcm', 'questions'));
    }






    public function createQcm(KnowledgeCreateRequest $request)
    {
        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . config('services.gemini.api_key');

        $langage = $request->input('langage');
        $number = $request->input('number');

        $prompt = <<<EOT
Tu es un professeur expert en informatique qui souhaite générer un questionnaire à choix multiples (QCM) pour ses élèves sur le langage {$langage}. Tu dois créer un tableau JSON contenant exactement {$number} questions, classées par niveau de difficulté : "débutant", "intermédiaire", et "avancé".

### Contraintes :
- Les questions doivent être **variées et uniques**, même si la même demande est répétée avec le même langage. Ne réutilise pas les mêmes formulations ou exemples si le langage {$langage} est redemandé ultérieurement.
- Si {$number} = 1, alors la seule question doit être de niveau "débutant". Il ne doit y avoir **aucune** question de niveau "intermédiaire" ou "avancé".
- Sinon, répartis les questions de manière équilibrée entre les trois niveaux de difficulté.
- Chaque question doit contenir exactement trois propositions de réponse : "answer_0", "answer_1", et "answer_2".
- Le champ "correct_answer" doit indiquer la clé correspondant à la bonne réponse (ex. : "answer_1").

### Format JSON attendu :
Réponds uniquement avec un tableau JSON **brut** (sans texte explicatif, sans balise markdown).
Chaque élément du tableau doit être un objet contenant :
- "question" : énoncé de la question
- "level" : "débutant", "intermédiaire" ou "avancé"
- "answer_0", "answer_1", "answer_2" : réponses proposées
- "correct_answer" : la clé correspondant à la bonne réponse

### Exemple :
[
  {
    "question": "Quelle est la différence entre une liste et un tuple en Python ?",
    "level": "intermédiaire",
    "answer_0": "Une liste est mutable, un tuple est immutable.",
    "answer_1": "Un tuple est mutable, une liste est immutable.",
    "answer_2": "Il n'y a pas de différence.",
    "correct_answer": "answer_0"
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

        $qcm = new Qcm();
        $qcm->name = $langage;
        $qcm->save();
        for ($i = 0; $i < sizeof($questions_json); $i++) {
            $questions = new Question();
            $questions->qcm_id = $qcm->id;
            $questions->question = $questions_json[$i]['question'];
            $questions->level = $questions_json[$i]['level'];
            $questions->answer_0 = $questions_json[$i]['answer_0'];
            $questions->answer_1 = $questions_json[$i]['answer_1'];
            $questions->answer_2 = $questions_json[$i]['answer_2'];
            $questions->correct_answer = $questions_json[$i]['correct_answer'];
            $questions->save();

        }


        return redirect()->route('knowledge.index');
    }



}
