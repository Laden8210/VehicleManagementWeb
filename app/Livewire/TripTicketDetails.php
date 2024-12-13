<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TripTicket;

class TripTicketDetails extends Component
{
    public $vehicle;
    public $driver;
    public $destination;
    public $arrivalDate;
    public $returnDate;

    protected $listeners = ['showTripDetails' => 'loadTripDetails'];

    public function loadTripDetails($tripDetails)
    {
        $this->vehicle = $tripDetails['vehicle'];
        $this->driver = $tripDetails['driver'];
        $this->destination = $tripDetails['destination'];
        $this->arrivalDate = $tripDetails['arrivalDate'];
        $this->returnDate = $tripDetails['returnDate'];
    }
    public function render()
    {
        return view('livewire.trip-ticket-details');
    }
}
