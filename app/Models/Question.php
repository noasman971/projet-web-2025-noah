<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{

    protected $fillable = [
        'bilan_id',
        'question',
        'level',
        'answer_0',
        'answer_1',
        'answer_2',
        'answer_3',
        'correct_answer'
    ];

    public function bilans()
    {
        return $this->belongsTo(CohortsBilans::class);
    }


}
