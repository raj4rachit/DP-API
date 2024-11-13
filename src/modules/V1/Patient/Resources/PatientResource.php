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
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", example="john@example.com"),
 *     @OA\Property(
 *         property="roles",
 *         type="array",
 *
 *         @OA\Items(type="string", example="user")
 *     ),
 *
 *     @OA\Property(property="gender",type="string", example="Male"),
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
