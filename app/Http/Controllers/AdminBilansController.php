<?php

namespace App\Http\Controllers;

use App\Models\CohortsBilans;
use Illuminate\Http\Request;

class AdminBilansController extends Controller
{
    public function index($id)
    {
        $id = decrypt($id);
        $qcm = CohortsBilans::find($id);
        $questions = $qcm->questions()->get();
        return view('pages.adminKnowledge.index', compact('questions', 'qcm'));
    }

}
