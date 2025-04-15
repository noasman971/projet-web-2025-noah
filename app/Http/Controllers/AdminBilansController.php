<?php

namespace App\Http\Controllers;

use App\Models\CohortsBilans;
use App\Models\UserBilans;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdminBilansController extends Controller
{
    /**
     * Display the page with the index of the bilans.
     * Allows the admin to see the grades of the students.
     * The student can only see his own grades.
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $id = decrypt($id);
        $qcm = CohortsBilans::find($id);
        $questions = $qcm->questions()->get();

        $user = auth()->user();

        if ($user->school()->pivot->role == 'student') {
            $user_bilans = UserBilans::where('bilan_id', $id)->where('user_id', $user->id)->get();
        } else {
            $user_bilans = UserBilans::where('bilan_id', $id)->get();
        }

        return view('pages.adminKnowledge.index', compact('questions', 'qcm', 'user_bilans'));
    }

}
