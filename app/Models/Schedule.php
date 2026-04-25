<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'clock_in_time',
        'clock_out_time',
    ];

    protected $casts = [
        'clock_in_time' => 'datetime:H:i',
        'clock_out_time' => 'datetime:H:i',
    ];

    public function employeeSchedules()
    {
        return $this->hasMany(EmployeeSchedule::class);
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_schedules')
            ->withPivot('day_of_week');
    }
}
