<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_code',
        'full_name',
        'phone',
        'address',
        'position',
        'join_date',
        'is_active',
    ];

    protected $casts = [
        'join_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function employeeSchedules()
    {
        return $this->hasMany(EmployeeSchedule::class);
    }

    public function schedules()
    {
        return $this->belongsToMany(Schedule::class, 'employee_schedules')
            ->withPivot('day_of_week');
    }
}
