<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommonCreateTaskRequest;
use App\Models\Cohort;
use App\Models\CommonTask;
use App\Policies\StudentPolicy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommonLifeController extends Controller
{


    /**
     * Display the page with the common tasks.
     * Allows the admin to see all the common tasks.
     * The student can only see the common tasks of his cohort.
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $user = auth()->user();

        if ($user->school()->pivot->role == 'student' && $user->cohort_id != null)
        {
            $commonTasks = CommonTask::where('cohort_id', $user->cohort_id)->get();
        }
        elseif ($user->school()->pivot->role == 'admin' || $user->school()->pivot->role == 'teacher')
        {
            $commonTasks = CommonTask::all();
        }
        else
        {
            $commonTasks = [];
        }

        $cohort = Cohort::all();
        return view('pages.commonLife.index', compact('commonTasks', 'cohort'));
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
        $id = decrypt($id);
        $common_task = CommonTask::findOrFail($id);

        $common_task->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'time' => $request->input('time'),
            'validate' => $request->input('select'),
            'cohort_id' => $request->input('cohort'),
        ]);


        $common_task->save();

        return redirect()->route('common-life.index');
    }


    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * Delete a common task
     */
    public function destroy($id) {
        $id = decrypt($id);

        $common_task = CommonTask::findOrFail($id);
        $common_task->delete();
        return redirect()->route('common-life.index');

    }


    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * The student validate a common task
     */
    public function pointer(Request $request, $id) {

        $id = decrypt($id);
        $common_task = CommonTask::findOrFail($id);
        $common_task->update([
            'commentary' => $request->input('comment'),
            'validate' =>1,
            'time' => Carbon::now(),
            'user_id' => auth()->user()->id,
        ]);
        return redirect()->route('common-life.index');
    }


}
