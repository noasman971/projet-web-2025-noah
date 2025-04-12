<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_bilans extends Model
{
    protected $table = 'users_bilans';

    protected $fillable = [
        'user_id',
        'bilan_id',
        'score',
        'completed_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cohorts_bilans()
    {
        return $this->belongsTo(CohortsBilans::class);
    }


}
