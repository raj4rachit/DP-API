<?php

declare(strict_types=1);

namespace Modules\V1\Patient\Resources;

use AllowDynamicProperties;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="PatientMedicalHistoryResource",
 *     title="Patient Medical History Resource",
 *     description="Representation of a patient's medical history, including details such as medical aid, race, ethnicity, and MRN number.",
 *
 *     @OA\Property(
 *         property="uuid",
 *         type="string",
 *         example="9e56f544-bbc5-4878-9f8d-543b8e68c67f",
 *         description="Unique identifier for the patient's medical history record (UUID)"
 *     ),
 *     @OA\Property(
 *         property="medical_aid",
 *         type="string",
 *         example="ABC Health Plan",
 *         description="The medical aid provider for the patient"
 *     ),
 *     @OA\Property(
 *         property="race",
 *         type="string",
 *         example="Caucasian",
 *         description="Race of the patient"
 *     ),
 *     @OA\Property(
 *         property="ethnicity",
 *         type="string",
 *         example="Hispanic",
 *         description="Ethnicity of the patient"
 *     ),
 *     @OA\Property(
 *         property="mrn_number",
 *         type="string",
 *         example="MRN-12345",
 *         description="Medical Record Number (MRN) of the patient"
 *     ),
 * )
 */

#[AllowDynamicProperties] final class PatientMedicalHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'medical_aid' => $this->medical_aid,
            'race' => $this->race,
            'ethnicity' => $this->ethnicity,
            'mrn_number' => $this->mrn_number,
        ];
    }
}
