<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\V1\Doctor\Models\Doctor;
use Modules\V1\Hospital\Models\Hospital;
use Modules\V1\Lab\Models\Lab;
use Modules\V1\Patient\Models\Patient;
use Modules\V1\Patient\Models\PatientDoctor;
use Modules\V1\User\Models\Role;
use Modules\V1\User\Models\User;
use Spatie\Permission\Models\Permission;

final class CreateOtherUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hospital = Hospital::create([
            'name' => 'HMS Hospital',
            'address_line_1' => 'CF 101',
            'address_line_2' => 'R Street',
            'city' => 'New York',
            'state' => 'NY',
            'country' => 'USA',
            'postal_code' => '10001',
            'phone' => '9876543210',
            'email' => 'updated@hospital.com',
            'description' => 'Updated description.',
            'status' => 'Active',
        ]);
        $hospitalID = $hospital->uuid;

        $user = User::create([
            'first_name' => 'Doctor',
            'last_name' => 'Admin',
            'email' => 'doctor@gmail.com',
            'password' => bcrypt('123456789'), // Make sure to hash passwords
            'user_type' => 'doctor',
            'email_verified_at' => now(),
        ]);

        // Find the role by its name
        $doctorRole = Role::where('name', 'doctor')->first();

        // Assign the 'admin' role to the user
        if ($doctorRole) {
            $permissions = Permission::where('name', 'like', 'doctor-%')->orWhere('name', 'like', 'patient-%')->pluck('uuid')->toArray();
            $doctorRole->syncPermissions($permissions);
            $user->assignRole([$doctorRole->uuid]);
        }
        $userID = $user->uuid;
        $doctor = Doctor::create([
            'user_id' => $userID,
            'hospital_id' => $hospitalID,
            'address_line_1' => 'CF 1011',
            'address_line_2' => 'R Street new',
            'city' => 'New York',
            'state' => 'NY',
            'country' => 'USA',
            'postal_code' => '10001',
            'contact_phone' => '9876543210',
            'email' => 'doctor@doctor.com',
        ]);
        $doctorId = $doctor->uuid;

        $user1 = User::create([
            'first_name' => 'Patient',
            'last_name' => 'Admin',
            'email' => 'patient@gmail.com',
            'password' => bcrypt('123456789'), // Make sure to hash passwords
            'user_type' => 'patient',
            'email_verified_at' => now(),
        ]);

        // Find the role by its name
        $patientRole = Role::where('name', 'patient')->first();

        // Assign the 'admin' role to the user
        if ($patientRole) {
            $permissions1 = Permission::where('name', 'like', 'patient-%')->pluck('uuid')->toArray();
            $patientRole->syncPermissions($permissions1);
            $user1->assignRole([$patientRole->uuid]);
        }
        $user1ID = $user1->uuid;
        $patient = Patient::create([
            'user_id' => $user1ID,
            'arn_number' => 'ASDC34345D',
            'address_line_1' => 'CF 1011',
            'address_line_2' => 'R Street new',
            'city' => 'New York',
            'state' => 'NY',
            'country' => 'USA',
            'postal_code' => '10001',
            'primary_phone' => '9876543210',
            'gender' => 'Male',
            'dob' => '1998-01-01',
            'id_type' => 'Card',
            'id_number' => '123456789',
            'languages' => ("English,Gujarati"),
        ]);
        $patientID = $patient->uuid;
        $patientDoctor = PatientDoctor::create([
            'patient_id' => $patientID,
            'doctor_id' => $doctorId,
        ]);


        $user2 = User::create([
            'first_name' => 'Lab',
            'last_name' => 'Admin',
            'email' => 'lab@gmail.com',
            'password' => bcrypt('123456789'), // Make sure to hash passwords
            'user_type' => 'lab',
            'email_verified_at' => now(),
        ]);

        // Find the role by its name
        $labRole = Role::where('name', 'lab')->first();

        // Assign the 'lab' role to the user
        if ($labRole) {
            $permissions2 = Permission::where('name', 'like', 'lab-%')->orWhere('name', 'like', 'report-%')->orWhere('name', 'like', 'patient-%')->pluck('uuid')->toArray();
            $labRole->syncPermissions($permissions2);
            $user2->assignRole([$labRole->uuid]);
        }
        $user2ID = $user2->uuid;
        $lab = Lab::create([
            'user_id' => $user2ID,
            'name' => 'Lab Test New',
            'address_line_1' => 'CF 1011',
            'address_line_2' => 'R Street new',
            'city' => 'New York',
            'state' => 'NY',
            'country' => 'USA',
            'postal_code' => '10001',
            'phone' => '9876543210',
        ]);
    }
}
