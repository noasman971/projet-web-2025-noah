<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommonTask extends Model
{
    protected $table        = 'common_tasks';
    protected $fillable     = ['name', 'description', 'time', 'validate', 'commentary', 'user_id', 'cohort_id'];

    public function cohort() {
        return $this->belongsTo(Cohort::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
