<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ReminderNotification;
use Illuminate\Support\Facades\Notification;

class Personnel extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [

        'Name',
        'Suffix',
        'DateOfBirth',
        'Age',
        'Gender',
        'CivilStatus',
        'MobileNumber',
        'EmailAddress',
        'Address',
        'EmployeeID',
        'Designation',
        'Status',
        'Section',
    ];

    public function setBirthdayAttribute($value)
    {
        $this->attributes['DateOfBirth'] = $value;
        $this->attributes['Age'] = Carbon::parse($value)->age;
    }

    public function getAgeAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['DateOfBirth'])->age;
    }

    public function roles()
    {
        return $this->hasMany(PersonnelRole::class, 'personnels_id');
    }

    public function tripticket()
    {
        return $this->hasMany(TripTicket::class, 'personnels_id');
    }

    public function maintenancerecommendation()
    {
        return $this->hasMany(MaintenanceRecommendation::class);
    }

    public function users()
    {
        return $this->hasMany(User::class); // If it's a one-to-many relationship
    }
}
