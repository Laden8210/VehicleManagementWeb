<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RepairHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'repair_requests_id',
    ];

    public function repairRequest()
    {
        return $this->belongsTo(RepairRequest::class, 'repair_requests_id'); // Adjust the foreign key if needed
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicles_id'); // Adjust the foreign key if needed
    }

    public function personnel()
    {
        return $this->belongsTo(Personnel::class, 'personnels_id'); // Adjust the foreign key if needed
    }


}
