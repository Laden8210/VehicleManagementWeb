<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Request extends Model
{
    use HasFactory;

    protected $fillable = [

        'GTNumber',
        'borrowers_id',
        'inventories_id',
        'Quantity',
        'NumberOfItems',
        'RequestDate',
        'ReturnDate',
        'Purpose',
        'RequestStatus'

    ];

    protected static function booted()
    {
        static::creating(function ($request) {
            $inventory = Inventory::find($request->inventories_id);

            if (!$inventory || $request->NumberOfItems > $inventory->ItemQuantity) {
                throw new \Exception('Requested quantity exceeds available stock.');
            }

            // Deduct the requested quantity from the inventory
            $inventory->decrement('ItemQuantity', $request->NumberOfItems);

            if (empty($request->GTNumber)) {
                $year = now()->year;
                $month = str_pad(now()->month, 2, '0', STR_PAD_LEFT);
                $lastId = self::max('id') + 1;

                $request->GTNumber = 'GP-' . $year . '-' . $month . '-' . str_pad($lastId, 3, '0', STR_PAD_LEFT);
            }
        });

        static::updating(function ($request) {
            $originalRequest = $request->getOriginal();
            $inventory = Inventory::find($request->inventories_id);

            if (!$inventory) {
                throw new \Exception('Inventory item not found.');
            }

            if ($request->RequestStatus === 'Returned') {
                // If returned, increment inventory quantity
                $inventory->increment('ItemQuantity', $request->NumberOfItems);
            } else {
                $previousQuantity = $originalRequest['NumberOfItems'];
                $newQuantity = $request->NumberOfItems;

                if ($newQuantity > $inventory->ItemQuantity + $previousQuantity) {
                    throw new \Exception('Requested quantity exceeds available stock.');
                }

                // Adjust inventory quantity based on the updated request
                $inventory->increment('ItemQuantity', $previousQuantity); // Restore previous quantity
                $inventory->decrement('ItemQuantity', $newQuantity); // Deduct new quantity
            }
        });
    }


    public function borrower(): BelongsTo
    {
        return $this->belongsTo(Borrower::class, 'borrowers_id');
    }

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class, 'inventories_id');
    }
}
