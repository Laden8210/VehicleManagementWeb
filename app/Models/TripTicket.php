<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class TripTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'TripTicketNumber',
        'ArrivalDate',
        'ReturnDate',
        'vehicles_id',
        'user_id',
        'responders',
        'Origin',
        'Destination',
        'Purpose',
        'KmBeforeTravel',
        'BalanceStart',
        'IssuedFromOffice',
        'TimeDeparture_A',
        'KmAfterTravel',
        'DistanceTravelled',
        'TimeArrival_A',
        'TimeDeparture_B',
        'TimeArrival_B',
        'AddedDuringTrip',
        'TotalFuelTank',
        'FuelConsumption',
        'BalanceEnd',
        'Others',
        'Remarks',
    ];

    protected static function booted()
    {
        static::creating(function ($tripTicket) {
            if (empty($tripTicket->TripTicketNumber)) {
                $year = now()->year;
                $month = now()->month;
                $tripTicket->TripTicketNumber = 'TT-' . $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . str_pad(TripTicket::max('id') + 1, 3, '0', STR_PAD_LEFT);
                Log::info('Generated TripTicketNumber: ' . $tripTicket->TripTicketNumber);
            }
        });
        static::saving(function ($trip) {
            $trip->DistanceTravelled = floatval($trip->KmAfterTravel) - floatval($trip->KmBeforeTravel);
            $trip->TotalFuelTank = round(floatval($trip->BalanceStart) + floatval($trip->IssuedFromOffice) + floatval($trip->AddedDuringTrip), 2);
            $trip->FuelConsumption = round($trip->DistanceTravelled / 10, 2);
            $trip->BalanceEnd = round($trip->TotalFuelTank - $trip->FuelConsumption, 2);

            Log::info('Trip Ticket Saving:', [
                'DistanceTravelled' => $trip->DistanceTravelled,
                'TotalFuelTank' => $trip->TotalFuelTank,
                'FuelConsumption' => $trip->FuelConsumption,
                'BalanceEnd' => $trip->BalanceEnd,
            ]);
        });

    }

    public function getTotalFuelTankAttribute()
    {
        return round(floatval($this->BalanceStart) + floatval($this->IssuedFromOffice) + floatval($this->AddedDuringTrip), 2);
    }


    // Accessor for DistanceTravelled
    public function getDistanceTravelledAttribute()
    {
        return $this->KmAfterTravel - $this->KmBeforeTravel;
    }

    // Accessor for FuelConsumption
    public function getFuelConsumptionAttribute()
    {
        return $this->DistanceTravelled / 10;
    }

    // Accessor for BalanceEnd
    public function getBalanceEndAttribute()
    {
        return $this->TotalFuelTank - $this->FuelConsumption;
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicles_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function personnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class, 'personnels_id');
    }

    public function fuelConsumptions()
    {
        return $this->hasMany(FuelConsumption::class, 'trip_tickets_id');
    }

    public function patient()
    {
        return $this->hasMany(Patient::class);
    }

    public function responders()
    {
        return $this->belongsToMany(Personnel::class, 'trip_ticket_personnel', 'trip_ticket_id', 'personnel_id');
    }

    public function getResponderNamesAttribute()
    {
        if (!is_array($this->responders) || empty($this->responders)) {
            return 'No Responder Assigned';
        }

        return Personnel::whereIn('id', $this->responders)->pluck('Name')->implode(', ');
    }

    protected $casts = [
        'responders' => 'array',
        'ArrivalDate' => 'datetime',
        'ReturnDate' => 'datetime',
    ];
}
