<?php

namespace App\Http\Controllers;

use App\Models\CommonTask;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $this->authorize('viewAnyStudent', CommonTask::class);
        $user_id = auth()->user();

        $commonTasks = CommonTask::where('user_id', $user_id->id)->get();

        return view('pages.history.index', compact('commonTasks'));
    }


}
