<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommonTask extends Model
{
    protected $table        = 'common_tasks';
    protected $fillable     = ['name', 'description', 'time', 'validate', 'commentary', 'user_id'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cohortTasks()
    {
        return $this->hasMany(Cohort_Task::class, 'cohort_task_id');
    }
}
