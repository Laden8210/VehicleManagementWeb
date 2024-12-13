<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Documents extends Model
{
    use HasFactory;

    protected $fillable = [

        'vehicles_id',
        'reminders_id',
        'DocumentType',
        'DocumentNumber',
        'IssueDate',
        'ExpirationDate'
    ];

    protected static function booted()
    {
        static::creating(function ($Documents) {
            if (empty($Documents->DocumentNumber)) {
                // Generate a unique number, e.g., "DOC-2024-10-0001"
                $year = now()->year;  // Current year
                $month = now()->month; // Current month
                $Documents->DocumentNumber = 'DOC-' . $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . str_pad(Documents::max('id') + 1, 4, '0', STR_PAD_LEFT);
                \Log::info('Generated DocumentNumber: ' . $Documents->DocumentNumber); // Log the generated number
            }
        });
    }

    public function reminder(): BelongsTo
    {
        return $this->belongsTo(Reminder::class, 'reminders_id');
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicles_id');
    }
}
