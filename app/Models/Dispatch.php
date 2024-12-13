<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispatch extends Model
{
    use HasFactory;

    protected $fillable = [

        'RequestDate',
        'RequestorName',
        'TravelDate',
        'PickupTime',
        'Destination',
        'RequestStatus',
        'Remarks',
    ];

    public function patient()
    {
        return $this->hasMany(Patient::class); 
    }
}
