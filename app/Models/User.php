<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'Full_name',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function hasPermission($permission)
    {
        $permissions = $this->group->permissions; // Collection of permission objects
        
        // Find the permission object for the specific page
        $permissionObject = $permissions->firstWhere('page', $permission);
    
        // Return true if permission object exists and view permission is true
        return $permissionObject && $permissionObject->view === 'true';
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class,'hrs_empolyees');
    }
    public function groups()
    {
        return $this->belongsTo(Group::class,'group_id');
    }
    public function subordinates()
    {
        return $this->hasMany(User::class, 'manager_id');
    }
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
