<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RepairRequest extends Model
{
    use HasFactory;

    protected $fillable = [

        'vehicles_id',
        'user_id',
        'RequestDate',
        'ReportedIssue',
        'Issues',
        'PriorityLevel',
        'RequestStatus',
        'DisapprovalComments',
        'RRNumber'

    ];

    protected static function booted()
    {
        static::creating(function ($repairRequest) {
            if (empty($repairRequest->RRNumber)) {
                $year = now()->year;
                $month = now()->month;
                $repairRequest->RRNumber = 'RR-' . $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . str_pad(RepairRequest::max('id') + 1, 3, '0', STR_PAD_LEFT);
                \Log::info('Generated RRNumber: ' . $repairRequest->RRNumber);
            }
        });
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicles_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function personnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class, 'personnels_id');
    }

    public function servicedetails()
    {
        return $this->hasOne(ServiceDetails::class, 'repair_requests_id');
    }

    public function expenses()
    {
        return $this->hasMany(Expenses::class, 'repair_requests_id');
    }
    public function repairHistory()
    {
        return $this->hasOne(RepairHistory::class, 'repair_requests_id');
    }




    protected $casts = [
        'Issues' => 'array',
    ];

    public function getIssuesAttribute($value)
    {
        return $value ?? [];
    }



}
