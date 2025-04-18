<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cohort extends Model
{
    protected $table        = 'cohorts';
    protected $fillable     = ['school_id', 'name', 'description', 'start_date', 'end_date'];

    public function cohortTasks()
    {
        return $this->hasMany(Cohort_Task::class, 'cohort_id');
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }




}
