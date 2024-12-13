<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [

        'user_id',
        'vehicles_id',
        'ReminderDate',
        'DueDate',
        'ReminderStatus',
        'Remarks'
    ];

    public function documents()
    {
        return $this->hasMany(Documents::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicles_id');
    }


    protected $attributes = [
        'ReminderStatus' => 'Sent',
    ];

}
