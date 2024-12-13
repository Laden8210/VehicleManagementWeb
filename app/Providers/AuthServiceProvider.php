<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\UserPolicy;
use App\Policies\ActivityPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Spatie\Activitylog\Models\Activity;

// use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [

        Borrower::class => BorrowerPolicy::class,
        Category::class => CategoryPolicy::class,
        Dispatch::class => DispatchPolicy::class,
        Documents::class => DocumentsPolicy::class,
        Expenses::class => ExpensesPolicy::class,
        FuelConsumption::class => FuelConsumptionPolicy::class,
        Inventory::class => InventoryPolicy::class,
        MaintenanceRecommendation::class => MaintenanceRecommendationPolicy::class,
        Patient::class => PatientPolicy::class,
        Permission::class => PermissionPolicy::class,
        Personnel::class => PersonnelPolicy::class,
        PersonnelRole::class => PersonnelRolePolicy::class,
        Reminder::class => ReminderPolicy::class,
        RepairRequest::class => RepairRequestPolicy::class,
        Request::class => RequestPolicy::class,
        Role::class => RolePolicy::class,
        ServiceDetails::class => ServiceDetailsPolicy::class,
        ServiceRecords::class => ServiceRecordsPolicy::class,
        Suppliers::class => SuppliersPolicy::class,
        Transaction::class => TransactionPolicy::class,
        TripTicket::class => TripTicketPolicy::class,
        User::class => UserPolicy::class,
        Vehicle::class => VehiclePolicy::class,
        VehicleRemarks::class => VehicleRemarksPolicy::class,
        Activity::class => ActivityPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
