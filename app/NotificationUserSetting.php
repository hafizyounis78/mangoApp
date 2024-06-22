<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationUserSetting extends Model
{
    protected $table = "notification_user_setting";
    protected $fillable = ["user_id" , "notification_type_id"];


}
