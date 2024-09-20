<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];
    public function permissions()
    {
        return $this->belongsToMany(Permission::class,'groups_permissions');
    }
    public function users()
    {
        return $this->belongsToMany(User::class,'users_groups');
    }
}