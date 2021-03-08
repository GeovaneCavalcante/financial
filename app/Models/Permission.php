<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['name', 'codename'];
}
