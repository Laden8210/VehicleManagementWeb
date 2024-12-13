<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'maintenance_recommendations_id'
    ];

    public function maintenancerecommendations(): BelongsTo
    {
        return $this->belongsTo(MaintenanceRecommendation::class, 'maintenance_recommendations_id');
    }
}
