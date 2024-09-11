<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annual_Holidays extends Model
{
    use HasFactory;
    protected $table = 'annual_holidays';
    public function employees()
    {
        return $this->belongsToMany(Employee::class,'employee_annual_holiyday');
    }
}
