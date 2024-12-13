<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [

        'ItemName',
        'ItemCode',
        'ItemDescription',
        'ItemUnit',
        'ItemQuantity',
        'ExpirationDate',
        'ItemStatus',
    ];

    protected static function booted()
    {
        static::creating(function ($item) {
            if (empty($item->ItemCode)) {
                $item->ItemCode = 'ITEM-' . strtoupper(uniqid());
            }
        });

        static::saving(function ($inventory) {
            // Automatically update ItemStatus based on ItemQuantity
            if ($inventory->ItemQuantity <= 0) {
                $inventory->ItemStatus = 'Not Available';
            } else {
                $inventory->ItemStatus = 'Available';
            }
        });
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'inventories_id');
    }

    public function request()
    {
        return $this->hasMany(Request::class, 'requests_items_id');
    }

    public function requests()
    {
        return $this->belongsToMany(Request::class, 'request_inventory')
            ->withPivot('quantity')
            ->withTimestamps();
    }
}
