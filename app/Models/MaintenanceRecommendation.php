<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaintenanceRecommendation extends Model
{
    use HasFactory;

    protected $fillable = [

        'vehicles_id',
        'user_id',
        'RecommendationType',
        'Issues',
        'RecommendationDate',
        'DueDate',
        'PriorityLevel',
        'RequestStatus',
        'DisapprovalComments',
        'MRNumber'

    ];

    protected static function booted()
    {
        static::creating(function ($maintenanceRecommendation) {
            if (empty($maintenanceRecommendation->MRNumber)) {
                // Generate a unique number, e.g., "MR-2024-10-001"
                $year = now()->year;  // Current year
                $month = str_pad(now()->month, 2, '0', STR_PAD_LEFT); // Current month
                $nextId = str_pad(MaintenanceRecommendation::count() + 1, 3, '0', STR_PAD_LEFT); // Generate the next ID
                $maintenanceRecommendation->MRNumber = 'MR-' . $year . '-' . $month . '-' . $nextId; // Format the Maintenance Recommendation Number
                \Log::info('Generated MRNumber: ' . $maintenanceRecommendation->MRNumber); // Log the generated number
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

    public function servicerecords()
    {
        return $this->hasOne(ServiceRecords::class, 'maintenance_recommendations_id');
    }

    public function expenses()
    {
        return $this->hasMany(Expenses::class, 'maintenance_recommendations_id');
    }




    protected $casts = [
        'Issues' => 'array',
    ];

    public function getIssuesAttribute($value)
    {
        return $value ?? [];
    }

}
