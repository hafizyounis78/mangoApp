<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Instruction extends Model
{
    protected $table = 'instructions';
    protected $fillable = ['image', 'orderBy'];
}
