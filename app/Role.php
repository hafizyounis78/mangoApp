<?php

namespace App;

use Illuminate\Support\Facades\Config;
use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    protected $fillable = ['name','display_name','description'];

    public function users()
    {
        return $this->belongsToMany(
            Config::get('auth.providers.users.model'),
            Config::get('entrust.role_user_table'),
            Config::get('entrust.role_foreign_key'),
            Config::get('entrust.user_foreign_key'));

    }
}
