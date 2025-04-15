<?php

namespace App\Http\Controllers;

use App\Models\CohortsBilans;
use App\Models\UserBilans;
use Illuminate\Http\Request;

class StudentBilansController extends Controller
{
    public function index($id, Request $request)
    {
        $id = decrypt($id);

        $qcm = CohortsBilans::findOrFail($id);
        $questions = $qcm->questions()->get();

        $questionIndex = (int) $request->input('questionIndex', -1);
        $note = (int) $request->input('qcmnote', 0);
        $userAnswer = $request->input('answer');

        if ($questionIndex >= 0 && $questionIndex < count($questions)) {
            $currentQuestion = $questions->get($questionIndex);
            if ($userAnswer === $currentQuestion->correct_answer) {
                $note++;
            }
        }

        if ($questionIndex === count($questions) - 1) {
            UserBilans::create([
                'user_id' => auth()->id(),
                'bilan_id' => $qcm->id,
                'score' => $note,
            ]);

            return redirect()->route('knowledge.index');
        }

        $i = $questionIndex + 1;


        return view('pages.studentKnowledge.index', compact('questions', 'qcm', 'i', 'note'));
    }

}
