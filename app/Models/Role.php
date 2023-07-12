<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    // ...

    public function userProfiles()
    {
        return $this->hasMany(UserProfile::class, 'role_id');
    }
}
