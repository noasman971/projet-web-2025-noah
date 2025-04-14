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

        $qcm = CohortsBilans::find($id);
        $questions = $qcm->questions()->get();



        $questionIndex = $request->input('questionIndex', -1);

        $qcmnote = $request->input('qcmnote', 0);
        $buttonsubmit = $request->input('answer');




        if ($questionIndex == count($questions)-1)
        {
            if ($buttonsubmit == $questions->get($questionIndex)->correct_answer)
            {
                $qcmnote++;
            }
            $cohort_bilans = new UserBilans();
            $cohort_bilans->user_id = auth()->user()->id;
            $cohort_bilans->bilan_id = $qcm->id;
            $cohort_bilans->score = $qcmnote;
            $cohort_bilans->save();

            return redirect()->route('knowledge.index');
        }
        else{
            $questionIndex = $questionIndex + 1;
        }



        $i = $questionIndex;
        $note = $qcmnote;





        if ($buttonsubmit == $questions->get($i)->correct_answer)
        {
            $note++;
        }









        return view('pages.studentKnowledge.index', compact('questions', 'qcm', 'i', 'note'));
    }




}
