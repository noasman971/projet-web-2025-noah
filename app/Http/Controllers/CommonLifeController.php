<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommonCreateTaskRequest;
use App\Models\CommonTask;
use Illuminate\Http\Request;

class CommonLifeController extends Controller
{
    public function index() {
        $commonTasks = CommonTask::all();

        return view('pages.commonLife.index', compact('commonTasks'));
    }

    /**
     * @param CommonCreateTaskRequest $request
     * @return void
     * Create a common task
     */
    public function create(CommonCreateTaskRequest $request) {
        $common_task = new CommonTask();
        $common_task->name = $request->input('name');
        $common_task->description = $request->input('description');
        $common_task->save();
        return redirect('/dashboard');
    }

    public function update(Request $request, $id) {
        $common_task = CommonTask::findOrFail($id);
        if ($request->name != null) {

            $common_task->update($request->name);
        }
        if($request->description != null) {
            $common_task->update($request->description);
        }
    }


}
