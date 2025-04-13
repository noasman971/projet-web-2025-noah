<?php

namespace App\Http\Controllers;

use App\Models\CohortsBilans;
use Illuminate\Http\Request;

class StudentBilansController extends Controller
{
    public function index($id, Request $request)
    {
        $id = decrypt($id);
        $qcm = CohortsBilans::find($id);
        $questions = $qcm->questions()->get();


        $questionIndex = $request->input('questionIndex', -1);
        $qcmnote = $request->input('qcmnote', -1);
        $qcmnote = $qcmnote + 1;
        $questionIndex = $questionIndex + 1;

        $i = $questionIndex;
        $note = $qcmnote;

        if ($i >= count($questions)) {
            $i = 0;
            $note = 0;
        }

        return view('pages.studentKnowledge.index', compact('questions', 'qcm', 'i', 'note'));
    }




}
