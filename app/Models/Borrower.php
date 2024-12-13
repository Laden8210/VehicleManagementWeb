<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Borrower extends Model
{
    use HasFactory;

    protected $fillable = [

        'BorrowerName',
        'BorrowerAddress',
        'BorrowerNumber',
        'BorrowerEmail',
    ];

    public function request()
    {
        return $this->hasMany(Request::class, 'borrowers_id');
    }

}
