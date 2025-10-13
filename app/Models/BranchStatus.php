<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchStatus extends Model
{
    protected $fillable = [
        'name',
        'sign',
        'color',
    ];
}
