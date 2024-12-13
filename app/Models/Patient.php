<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [

        'PatientName',
        'Gender',
        'Age',
        'PatientNumber',
        'PatientAddress',
        'PatientDiagnosis',
        'dispatches_id',
        'trip_tickets_id',
    ];

    public function dispatch(): BelongsTo
    {
        return $this->belongsTo(Dispatch::class, 'dispatches_id');
    }

    public function tripticket(): BelongsTo
    {
        return $this->belongsTo(TripTicket::class, 'trip_tickets_id');
    }

        

    
}
