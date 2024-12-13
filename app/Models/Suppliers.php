<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Suppliers extends Model
{
    use HasFactory;

    protected $fillable = [
        
            'SupplierName',
            'ContactPerson',
            'Designation',
            'MobileNumber',
            'CompleteAddress',
            'EmailAddress',
            'YearEstablished',
            'PhilgepsMembership'
    ];

    public function servicedetails()
    {
        return $this->hasMany(ServiceDetails::class); 
    }

    
    public function servicerecords()
    {
        return $this->hasMany(ServiceRecords::class); 
    }
}
