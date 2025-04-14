<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CohortsBilans extends Model
{

    protected $fillable = [
        'name',
        'cohort_id',
        'link'
    ];

    public function questions()
    {
        return $this->hasMany(Question::class, 'bilans_id');
    }

    public function cohort()
    {
        return $this->belongsTo(Cohort::class);
    }

    public function user_bilans()
    {
        return $this->hasMany(UserBilans::class, 'bilan_id');
    }


}
