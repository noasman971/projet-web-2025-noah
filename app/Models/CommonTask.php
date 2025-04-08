<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommonTask extends Model
{
    protected $table        = 'common_tasks';
    protected $fillable     = ['name', 'description', 'time', 'validate'];
}
