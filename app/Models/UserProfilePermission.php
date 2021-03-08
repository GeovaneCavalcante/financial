<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class UserProfilePermission extends Model
{
    protected $fillable = ['user_profile_id', 'permission_id'];
}
