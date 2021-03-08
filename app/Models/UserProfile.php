<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class UserProfile extends Model
{
    protected $fillable = ['name'];

    /**
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'user_profile_permissions',
            'user_profile_id',
            'permission_id'
        );
    }
}
