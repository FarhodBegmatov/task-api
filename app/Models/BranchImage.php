<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchImage extends Model
{
    protected $fillable = [
        'branch_id',
        'path',
    ];
}
