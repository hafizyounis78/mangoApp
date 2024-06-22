<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $table = "languages";
    protected $fillable = ["lng_name" , "lng_originalName" , "lng_slug"];
}
