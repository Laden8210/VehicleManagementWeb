<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'VehicleName',
        'MvfileNo',
        'PlateNumber',
        'EngineNumber',
        'ChassisNumber',
        'Fuel',
        'Make',
        'Series',
        'BodyType',
        'YearModel',
        'Color',
        'PurchasedDate',
        'RegistrationDate',
        'OrcrNo',
        'PurchasedCost',
        'PropertyNumber',
    ];

    public function remarks()
    {
        return $this->hasMany(VehicleRemarks::class, 'vehicles_id');
    }

    public function documents()
    {
        return $this->hasMany(Documents::class);
    }

    public function tripticket()
    {
        return $this->hasMany(TripTicket::class);
    }

    public function maintenancerecommendation()
    {
        return $this->hasMany(MaintenanceRecommendation::class);
    }

    public function repairrequest()
    {
        return $this->hasMany(RepairRequest::class);
    }
}
