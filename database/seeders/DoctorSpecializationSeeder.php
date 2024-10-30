<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\V1\Doctor\Models\DoctorSpecialization;
use Modules\V1\Report\Models\Report;

final class DoctorSpecializationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specializations = [
            [
                'name' => 'Cardiologist',
                'code' => 'CD',
                'description' => 'Heart and cardiovascular system specialist',
            ],
            [
                'name' => 'Endocrinologist',
                'code' => 'EH',
                'description' => 'Hormone and gland disorders specialist',
            ],
            [
                'name' => 'Gastroenterologist',
                'code' => 'GE',
                'description' => 'Digestive system specialist',
            ],
            [
                'name' => 'Hematologist',
                'code' => 'HE',
                'description' => 'Blood disorders specialist',
            ],
            [
                'name' => 'Nephrologist',
                'code' => 'NE',
                'description' => 'Kidney specialist',
            ],
            [
                'name' => 'Neurologist',
                'code' => 'NL',
                'description' => 'Brain and nervous system specialist',
            ],
            [
                'name' => 'Oncologist',
                'code' => 'ON',
                'description' => 'Cancer specialist',
            ],
            [
                'name' => 'Ophthalmologist',
                'code' => 'OP',
                'description' => 'Eye specialist and surgeon',
            ],
            [
                'name' => 'Orthopedic Surgeon',
                'code' => 'OS',
                'description' => 'Bone, joint, and muscle specialist',
            ],
            [
                'name' => 'Pediatrician',
                'code' => 'PD',
                'description' => 'Child and adolescent health specialist',
            ],
            [
                'name' => 'Psychiatrist',
                'code' => 'PS',
                'description' => 'Mental health and behavior specialist',
            ],
            [
                'name' => 'Pulmonologist',
                'code' => 'PU',
                'description' => 'Lung and respiratory system specialist',
            ],
            [
                'name' => 'Rheumatologist',
                'code' => 'RH',
                'description' => 'Joint, muscle, and autoimmune disease specialist',
            ],
            [
                'name' => 'Dermatologist',
                'code' => 'DM',
                'description' => 'Skin, hair, and nail specialist',
            ],
            [
                'name' => 'Gynecologist',
                'code' => 'GY',
                'description' => 'Female reproductive system specialist',
            ],
            [
                'name' => 'Obstetrician',
                'code' => 'OB',
                'description' => 'Pregnancy and childbirth specialist',
            ],
            [
                'name' => 'Urologist',
                'code' => 'UR',
                'description' => 'Urinary tract and male reproductive system specialist',
            ],
            [
                'name' => 'Anesthesiologist',
                'code' => 'AN',
                'description' => 'Pain management and anesthesia specialist',
            ],
            [
                'name' => 'Pathologist',
                'code' => 'PA',
                'description' => 'Disease diagnosis specialist through laboratory analysis',
            ],
            [
                'name' => 'Radiologist',
                'code' => 'RA',
                'description' => 'Medical imaging and diagnosis specialist',
            ],
            [
                'name' => 'Infectious Disease Specialist',
                'code' => 'ID',
                'description' => 'Specialist in infectious diseases',
            ],
            [
                'name' => 'Allergist/Immunologist',
                'code' => 'AI',
                'description' => 'Allergy and immune system specialist',
            ],
            [
                'name' => 'Sports Medicine Specialist',
                'code' => 'SM',
                'description' => 'Injuries related to sports and exercise',
            ],
            [
                'name' => 'Geriatrician',
                'code' => 'GR',
                'description' => 'Health of elderly people',
            ],
            [
                'name' => 'Otolaryngologist (ENT)',
                'code' => 'ENT',
                'description' => 'Ear, nose, and throat specialist',
            ],
        ];

        foreach ($specializations as $data) {
            $specialization = new DoctorSpecialization();
            $specialization->name = $data['name'];
            $specialization->code = $data['code'];
            $specialization->description = $data['description'];
            $specialization->save();
        }
    }
}
