<x-filament-panels::page>
    <div class="space-y-4">
        <h2 class="text-xl font-bold">Vehicle Remarks Overview</h2>
        <div><strong>Serviceable:</strong> {{ $serviceableCount }}</div>
        <div><strong>Unserviceable:</strong> {{ $unserviceableCount }}</div>
        <div><strong>Under Maintenance:</strong> {{ $underMaintenanceCount }}</div>
        <div><strong>For PRS:</strong> {{ $forPRSCount }}</div>
    </div>
</x-filament-panels::page>