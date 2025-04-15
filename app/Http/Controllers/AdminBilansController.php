<?php

namespace App\Http\Controllers;

use App\Models\CohortsBilans;
use App\Models\UserBilans;
use Illuminate\Http\Request;

class AdminBilansController extends Controller
{
    public function index($id)
    {
        $id = decrypt($id);
        $qcm = CohortsBilans::find($id);
        $questions = $qcm->questions()->get();



        $user_bilans = UserBilans::where('bilan_id', $id)->get();




        return view('pages.adminKnowledge.index', compact('questions', 'qcm', 'user_bilans'));
    }

}
