<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Qcm extends Model
{

    protected $fillable = [
        'name',
        'cohort_id',
        'link'
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function cohort()
    {
        return $this->belongsTo(Cohort::class);
    }


}
