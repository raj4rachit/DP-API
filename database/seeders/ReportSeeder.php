<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\V1\Report\Models\Report;

final class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reports = [
            [
                'name' => 'Blood Test',
                'description' => 'Detailed blood work analysis',
            ],
            [
                'name' => 'MRI',
                'description' => 'Magnetic Resonance Imaging',
            ],
            [
                'name' => 'Complete Blood Count (CBC)',
                'description' => 'Standard blood test to evaluate overall health',
            ],
            [
                'name' => 'Urinalysis',
                'description' => 'Tests urine to detect a range of disorders, such as infections.',
            ],
            [
                'name' => 'Thyroid Function Test',
                'description' => 'Assesses thyroid hormone levels.',
            ],
            [
                'name' => 'Basic Metabolic Panel (BMP)',
                'description' => 'Measures blood sugar, calcium, and electrolytes.',
            ],
            [
                'name' => 'Liver Function Test (LFT)',
                'description' => 'Tests for liver health and function.',
            ],
            [
                'name' => 'X-Ray',
                'description' => 'Imaging test for bones and certain internal organs.',
            ],
            [
                'name' => 'MRI',
                'description' => 'Detailed imaging test using magnetic fields.',
            ],
            [
                'name' => 'CT Scan',
                'description' => 'Cross-sectional imaging test.',
            ],
            [
                'name' => 'Echocardiogram',
                'description' => 'Ultrasound of the heart',
            ],
            [
                'name' => 'Bone Density Scan',
                'description' => 'Measures bone health and detects osteoporosis',
            ],
            [
                'name' => 'Allergy Test',
                'description' => 'Detects various allergens.',
            ],
            [
                'name' => 'Blood Glucose Test',
                'description' => 'Measures blood sugar levels.',
            ],
            [
                'name' => 'COVID-19 PCR Test',
                'description' => 'Detects the presence of COVID-19 virus',
            ],
            [
                'name' => 'Mammogram',
                'description' => 'X-ray test for breast examination.',
            ],
        ];

        foreach ($reports as $data) {
            $report = new Report();
            $report->name = $data['name'];
            $report->description = $data['description'];
            $report->save();
        }
    }
}
