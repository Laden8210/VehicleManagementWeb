<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transport extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'personnel_id',
        'destination',
        'arrival_date',
        'return_date',
    ];

    // Define relationships
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class); // Adjust based on your Vehicle model
    }

    public function personnel()
    {
        return $this->belongsTo(Personnel::class); // Adjust based on your Personnel model
    }
}
