<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceDetails extends Model
{
    use HasFactory;

    protected $fillable = [

        'repair_requests_id',
        'suppliers_id',
        'RepairDate',
        'RepairType',
        'ServiceDescription',
        'ChangedParts',
        'ServiceCosts'

    ];

    public function repairrequests(): BelongsTo
    {
        return $this->belongsTo(RepairRequest::class, 'repair_requests_id');
    }

    public function supplierS(): BelongsTo
    {
        return $this->belongsTo(Suppliers::class, 'suppliers_id');
    }

}
