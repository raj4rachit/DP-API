<?php

declare(strict_types=1);

namespace Modules\V1\Patient\Resources;

use AllowDynamicProperties;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'medical_aid' => $this->medical_aid,
            'race' => $this->race,
            'ethnicity' => $this->ethnicity,
            'mrn_number' => $this->mrn_number,
        ];
    }
}
