<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['value', 'receiver_id', 'sender_id'];
}
