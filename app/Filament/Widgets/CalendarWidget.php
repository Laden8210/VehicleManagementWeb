<?php

namespace App\Filament\Widgets;

use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Saade\FilamentFullCalendar\Data\EventData;
use App\Filament\Resources\TripTicketResource;
use App\Models\TripTicket;

class CalendarWidget extends FullCalendarWidget
{
    protected static ?int $sort = 4;

    public function fetchEvents(array $fetchInfo): array
    {
        return TripTicket::with(['vehicle', 'personnel'])
            ->where(function ($query) use ($fetchInfo) {
                $query->where('ArrivalDate', '>=', $fetchInfo['start'])
                    ->orWhere('ReturnDate', '<=', $fetchInfo['end']);
            })
            ->get()
            ->map(
                fn(TripTicket $tripTicket) => EventData::make()
                    ->id($tripTicket->id)
                    ->title(
                        'Vehicle: ' . ($tripTicket->vehicle->VehicleName ?? 'N/A') . ' | ' .
                        'Driver: ' . ($tripTicket->personnel->Name ?? 'N/A') . ' | ' .
                        'Destination: ' . $tripTicket->Destination . ' | ' .
                        'Arrival Date: ' . $tripTicket->ArrivalDate->format('F j, Y') . ' | ' .
                        'Return Date: ' . ($tripTicket->ReturnDate ? $tripTicket->ReturnDate->format('F j, Y') : 'N/A')
                    )
                    ->start($tripTicket->ArrivalDate->toIso8601String())
                    ->end($tripTicket->ReturnDate ? $tripTicket->ReturnDate->toIso8601String() : null)
                    ->extendedProps([
                        'vehicle' => $tripTicket->vehicle->VehicleName ?? 'N/A',
                        'driver' => $tripTicket->personnel->Name ?? 'N/A',
                        'destination' => $tripTicket->Destination
                    ])
                    ->url(
                        TripTicketResource::getUrl(name: 'view', parameters: ['record' => $tripTicket]),
                        shouldOpenUrlInNewTab: true
                    )
                    ->backgroundColor($this->getEventColor($tripTicket))
                    ->borderColor($this->getEventColor($tripTicket))
            )
            ->toArray();
    }
    private function getEventColor(TripTicket $tripTicket): string
    {

        switch ($tripTicket->status) {
            case 'Serviceable':
                return '#3d9970';
            case 'Unserviceable':
                return '#f6b93c';
            case 'Under Maintenance':
                return '#e63946';
            default:
                return '#4a92e5';
        }
    }

    public function calendarOptions(): array
    {
        return [
            'initialView' => 'dayGridMonth',
            'selectable' => false,
            'editable' => false,
            'select' => null,
            'eventClick' => 'function(info) {
                const event = info.event;
                const vehicle = event.extendedProps.vehicle;
                const driver = event.extendedProps.driver;
                const destination = event.extendedProps.destination;
                const arrivalDate = new Date(event.start).toLocaleDateString("en-US", { year: "numeric", month: "long", day: "numeric" });
                const returnDate = event.end ? new Date(event.end).toLocaleDateString("en-US", { year: "numeric", month: "long", day: "numeric" }) : "N/A";

                // Emit Livewire event to show modal
                window.Livewire.emit("showTripDetails", {
                    vehicle: vehicle,
                    driver: driver,
                    destination: destination,
                    arrivalDate: arrivalDate,
                    returnDate: returnDate
                });

                // Trigger modal popup
                const modal = document.getElementById("trip-details-modal");
                if (modal) {
                    modal.showModal();
                }
            }',
        ];
    }
    public static function canView(): bool
    {
        return auth()->user()->hasAnyRole(['Admin', 'Driver']);
    }
}
