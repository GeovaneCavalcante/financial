<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = ['balance', 'user_id'];
}
