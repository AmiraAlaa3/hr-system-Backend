<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Annual_Holidays extends Model
{
    use HasFactory;
    protected $table = 'annual_holidays';

    protected $fillable = [
        'id',
        'date',
        'title',
        'description',
        'from_date',
        'to_date',
        'numberOfDays',
    ];
    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $from = Carbon::parse($model->from_date);
            $to = Carbon::parse($model->to_date);
            $model->numberOfDays = $to->diffInDays($from) + 1; 
        });
    }
    public function employees()
    {
        return $this->belongsToMany(Employee::class,'employee_annual_holiyday');
    }
}