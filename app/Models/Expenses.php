<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expenses extends Model
{
    use HasFactory;

    protected $fillable = [
        'repair_requests_id',
        'maintenance_recommendations_id',
        'RepairMaintenanceDate',
        'Description',
        'TotalCost',
        'AppropriationBudget',
        'AppropriationBalance',
        'PaymentType',
        'PaymentStatus',
        'DvNumber'
    ];

    public function repairrequests(): BelongsTo
    {
        return $this->belongsTo(RepairRequest::class, 'repair_requests_id');
    }

    public function maintenancerecommendations(): BelongsTo
    {
        return $this->belongsTo(MaintenanceRecommendation::class, 'maintenance_recommendations_id');
    }

    public function serviceDetails()
    {
        return $this->hasOne(ServiceDetails::class, 'repair_requests_id', 'repair_requests_id');
    }

    public function servicerecords()
    {
        return $this->hasOne(ServiceRecords::class, 'maintenance_recommendations', 'maintenance_recommendations_id');
    }

    protected static function booted()
    {
        static::creating(function ($expense) {
            $lastExpense = self::latest()->first();
            $expense->AppropriationBudget = $lastExpense ? $lastExpense->AppropriationBalance : 0;
            $expense->AppropriationBalance = $expense->AppropriationBudget - ($expense->TotalCost ?? 0);
        });

        static::saving(function ($expense) {
            $expense->AppropriationBalance = $expense->AppropriationBudget - ($expense->TotalCost ?? 0);
            \Log::info('Saving Expense:', [
                'AppropriationBudget' => $expense->AppropriationBudget,
                'TotalCost' => $expense->TotalCost,
                'AppropriationBalance' => $expense->AppropriationBalance,
            ]);
        });
    }

}
