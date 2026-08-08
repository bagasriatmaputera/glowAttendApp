<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingChangeRequest extends Model
{
    use HasFactory;

    public const TYPE_DELETE_EMPLOYEE = 'delete_employee';

    public const TYPE_DELETE_OFFICE_LOCATION = 'delete_office_location';

    public const TYPE_DELETE_ANNOUNCEMENT = 'delete_announcement';

    public const STATUS_PENDING = 'pending';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'type',
        'payload',
        'requested_by',
        'status',
        'reason',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'payload' => 'json',
        'approved_at' => 'datetime',
    ];

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function typeLabel(): string
    {
        return match ($this->type) {
            self::TYPE_DELETE_EMPLOYEE => 'Hapus Karyawan',
            self::TYPE_DELETE_OFFICE_LOCATION => 'Hapus Lokasi Kantor',
            self::TYPE_DELETE_ANNOUNCEMENT => 'Hapus Pengumuman',
            default => ucfirst(str_replace('_', ' ', $this->type)),
        };
    }
}
