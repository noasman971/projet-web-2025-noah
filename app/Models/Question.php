<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{

    protected $fillable = [
        'qcm_id',
        'question',
        'level',
        'answer_0',
        'answer_1',
        'answer_2',
        'correct_answer'
    ];

    public function qcm()
    {
        return $this->belongsTo(Qcm::class);
    }


}
