<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Weekend extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'date',
        'days'
    ];
    public function employee()
    {
        return $this->belongsToMany(Employee::class,'employee_weekend');
    }
}
