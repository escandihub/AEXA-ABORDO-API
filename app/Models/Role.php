<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    
/**
 * model_is save user_id
 */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'model_has_roles', 'model_id', 'role_id');
    }

 public function users()
 {
     return $this->belongsToMany(User::class, 'model_has_roles', 'role_id', 'model_id');
 }   
}
