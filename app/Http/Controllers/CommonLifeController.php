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

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * Update the informations of the commons tasks
     */
    public function update(Request $request, $id) {
        $common_task = CommonTask::findOrFail($id);

        $common_task->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'time' => $request->input('time'),
            'validate' => $request->input('select'),
        ]);

        $common_task->created_at = $request->input('created');

        $common_task->save();

        return redirect()->route('common-life.index');
    }


}
