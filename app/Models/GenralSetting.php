<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenralSetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'weekend1',
        'weekend2',
        'bonusHours',
        'deductionsHours'
    ];
    public function employee()
    {
        return $this->belongsToMany(Employee::class,'employee_setting');
    }
}