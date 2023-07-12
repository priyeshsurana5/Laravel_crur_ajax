<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    // protected $table = 'user_profiles';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'description',
        'role_id',
        'profile_image',
    ];

    /**
     * Get the role associated with the user profile.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
