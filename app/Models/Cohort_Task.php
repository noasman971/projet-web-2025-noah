<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cohort_Task extends Model
{
    protected $table        = 'cohort_tasks';
    protected $fillable     = ['cohort_id', 'common_task_id'];

    public function cohort() {
        return $this->belongsTo(Cohort::class);
    }
    public function commonTask() {
        return $this->belongsTo(CommonTask::class);
    }

}
