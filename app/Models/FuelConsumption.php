<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class FuelConsumption extends Model
{
    use HasFactory;

    protected $fillable = [

        'WithdrawalSlipNo',
        'PONum',
        'RequestDate',
        'ReferenceNumber',
        'trip_tickets_id',
        'Quantity',
        'Price',
        'Amount',
        'PreviousBalance',
        'RemainingBalance',
        'vehicles_id',
        'personnels_id',
    ];

    public function getRemainingBalanceAttribute()
    {
        return $this->PreviousBalance - ($this->Amount ?? 0); // Ensure Amount is considered
    }

    protected static function booted()
    {
        static::creating(function ($withdrawalSlip) {
            if (empty($withdrawalSlip->WithdrawalSlipNo)) {
                // Generate a unique number, e.g., "FC-2024-10-001"
                $year = now()->year;  // Current year
                $month = now()->month; // Current month
                $withdrawalSlip->WithdrawalSlipNo = 'WS-' . $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . str_pad(FuelConsumption::max('id') + 1, 3, '0', STR_PAD_LEFT);

                Log::info('Generated WithdrawalSlipNo: ' . $withdrawalSlip->WithdrawalSlipNo); // Log the generated number
            }
        });
    }

    public function tripticket(): BelongsTo
    {
        return $this->belongsTo(TripTicket::class, 'trip_tickets_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function personnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class, 'personnels_id');
    }

}
