<?php

declare(strict_types=1);

namespace Modules\V1\Patient\Resources;

use AllowDynamicProperties;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\V1\User\Resources\UserResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="PatientResource",
 *     title="Patient Resource",
 *     description="Patient resource representation",
 *
 *     @OA\Property(property="uuid", type="string", example="12345-abcde-67890-fghij"),
 *     @OA\Property(property="gender", type="string", example="Male"),
 *     @OA\Property(property="dob", type="string", format="date", example="1985-06-15"),
 *     @OA\Property(property="address_line_1", type="string", example="123 Main St."),
 *     @OA\Property(property="address_line_2", type="string", example="Apt 4B"),
 *     @OA\Property(property="city", type="string", example="New York"),
 *     @OA\Property(property="state", type="string", example="NY"),
 *     @OA\Property(property="country", type="string", example="USA"),
 *     @OA\Property(property="postal_code", type="string", example="10001"),
 *     @OA\Property(property="arn_number", type="string", example="A123456789"),
 *     @OA\Property(property="id_type", type="string", example="Passport"),
 *     @OA\Property(property="id_number", type="string", example="123456789"),
 *     @OA\Property(property="marital_status", type="string", example="Married"),
 *     @OA\Property(property="primary_phone", type="string", example="123-456-7890"),
 *     @OA\Property(property="home_phone", type="string", example="123-555-7890"),
 *     @OA\Property(property="work_phone", type="string", example="123-666-7890"),
 *     @OA\Property(property="secondary_phone", type="string", example="123-777-7890"),
 *     @OA\Property(property="languages", type="array",
 *         @OA\Items(type="string", example="English"),
 *         description="Languages spoken by the patient"
 *     ),
 *     @OA\Property(property="user", ref="#/components/schemas/UserResource"),
 *     @OA\Property(
 *         property="medical_history",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/PatientMedicalHistoryResource"),
 *         description="Medical history of the patient"
 *     ),
 * )
 */

#[AllowDynamicProperties] final class PatientResource extends JsonResource
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
            'gender' => $this->gender,
            'dob' => $this->dob,
            'address_line_1' => $this->address_line_1,
            'address_line_2' => $this->address_line_2,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'postal_code' => $this->postal_code,
            'arn_number' => $this->arn_number,
            'id_type' => $this->id_type,
            'id_number' => $this->id_number,
            'marital_status' => $this->marital_status,
            'primary_phone' => $this->primary_phone,
            'home_phone' => $this->home_phone,
            'work_phone' => $this->work_phone,
            'secondary_phone' => $this->secondary_phone,
            'languages' => $this->languages,
            'user' => new UserResource($this->whenLoaded('user')),
            'medical_history' => PatientMedicalHistoryResource::collection($this->whenLoaded('medicalHistories')),
        ];
    }
}
