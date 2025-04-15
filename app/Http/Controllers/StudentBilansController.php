<?php

namespace App\Http\Controllers;

use App\Models\CohortsBilans;
use App\Models\UserBilans;
use Illuminate\Http\Request;

class StudentBilansController extends Controller
{
    /**
     * Display the page for the student to answer the QCM.
     * Each time the student answers a question, the score is updated and the index of the question is updated also.
     * When the student finishes the QCM, the score is saved in the database.
     * @param  string  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index($id, Request $request)
    {
        $id = decrypt($id);

        $qcm = CohortsBilans::findOrFail($id);
        $questions = $qcm->questions()->get();

        $questionIndex = (int) $request->input('questionIndex', -1);
        $note = (int) $request->input('qcmnote', 0);
        $userAnswer = $request->input('answer');

        /**
         * If the index of the question is between 0 and the number of questions-1, we can check the answer
         * if the answer is correct, we increment the note
         **/
        if ($questionIndex >= 0 && $questionIndex < count($questions)) {
            $currentQuestion = $questions->get($questionIndex);
            if ($userAnswer === $currentQuestion->correct_answer) {
                $note++;
            }
        }

        // if we are on the last question, we save the score in the database
        if ($questionIndex === count($questions) - 1) {
            UserBilans::create([
                'user_id' => auth()->id(),
                'bilan_id' => $qcm->id,
                'score' => $note,
            ]);

            return redirect()->route('knowledge.index');
        }

        // update the index of the question
        $i = $questionIndex + 1;


        return view('pages.studentKnowledge.index', compact('questions', 'qcm', 'i', 'note'));
    }

}
