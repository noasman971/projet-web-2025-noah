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
        return redirect()->route('common-life.index');
    }

    public function update(Request $request, $id) {
        $common_task = CommonTask::findOrFail($id);

        $name = $request->input('name');
        $description = $request->input('description');
        $validate = $request->input('select');
        $created = $request->input('created');
        $time = $request->input('time');

        if ($name){
            $common_task->name = $name;
        }
        if ($description){
            $common_task->description = $description;
        }
        if ($validate){
            $common_task->validate = $validate;
        }
        if ($created){
            $common_task->created_at = $created;
        }
        if ($time){
            $common_task->time = $time;
        }
        $common_task->save();

        return redirect()->route('common-life.index');
    }


}
