<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceRecords extends Model
{
    use HasFactory;

    protected $fillable = [

        'maintenance_recommendations_id',
        'suppliers_id',
        'MaintenanceDate',
        'MaintenanceType',
        'ServiceDescription',
        'ChangedParts',
        'ServiceCosts'

    ];

    public function maintenancerecommendation(): BelongsTo
    {
        return $this->belongsTo(MaintenanceRecommendation::class, 'maintenance_recommendations_id');
    }

    public function supplierS(): BelongsTo
    {
        return $this->belongsTo(Suppliers::class, 'suppliers_id');
    }
}
