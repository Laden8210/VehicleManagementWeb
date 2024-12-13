<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;



class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin user and assign Admin role
        $admin = User::factory()->create([
            'name' => 'Jerick Jay Verbal',
            'email' => 'jiaamethyst101@gmail.com',
            'password' => Hash::make('jiaamethyst'),
        ]);
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $admin->assignRole($adminRole);


        $storekeeper = User::factory()->create([
            'name' => 'William Bernardo',
            'email' => 'williambernardo@gmail.com',
            'password' => Hash::make('WILLIAMBERNARDO'),
        ]);
        $storekeeperRole = Role::firstOrCreate(['name' => 'Storekeeper']);
        $storekeeper->assignRole($storekeeperRole);


        $driverRole = Role::firstOrCreate(['name' => 'Driver']);

        // Define arrays for names and emails
        $driverNames = [
            'Renato B. Felizardo, Jr.',
            'Ricky D. Baldove',
            'Bernie P. Lopez',
            'Erwin Earl C. Maitim',
            'Fritz G. Dayaday',
            'Ian Anton M. Rosete',
            'Khin Oliver C. Gonzales',
            'Kitz C. Adelmita',
            'Angelo D. Adelmita',
        ];

        $driverEmails = [
            'renatobfelizardo@gmail.com',
            'rickydbaldove@gmail.com',
            'bernieplopez@gmail.com',
            'erwinearlcmaitim@gmail.com',
            'fritzgdayaday@gmail.com',
            'ianantonmrosete@gmail.com',
            'khinolivercgonzales@gmail.com',
            'kitzcadelmita@gmail.com',
            'angelodadelmita@gmail.com',
        ];

        // Define passwords
        $driverPasswords = [
            'RENATOFELIZARDO',
            'RICKYBALDOVE',
            'BERNIELOPEZ',
            'ERWINEARLMAITIM',
            'FRITZGDAYADAY',
            'IANANTONROSETE',
            'KHINOLIVERGONZALES',
            'KITZADELMITA',
            'ANGELOADELMITA',
        ];

        // Loop to create 9 Driver users
        for ($i = 0; $i < count($driverNames); $i++) {
            $driver = User::factory()->create([
                'name' => $driverNames[$i],
                'email' => $driverEmails[$i],
                'password' => Hash::make($driverPasswords[$i]),
            ]);

            // Assign the Driver role to each user
            $driver->assignRole($driverRole);
        }
    }
}
