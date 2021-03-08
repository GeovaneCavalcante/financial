<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    protected $fillable = ['full_name', 'email', 'cpf', 'password', 'profile_id'];

    protected $hidden = ['password'];

    /**
     * @return HasOne
     */
    public function profile(): HasOne
    {
        return $this->hasOne(
            UserProfile::class,
            'id',
            'profile_id'
        );
    }

    /**
     * @return BelongsTo
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(
            Wallet::class,
            'id',
            'user_id'
        );
    }
}
