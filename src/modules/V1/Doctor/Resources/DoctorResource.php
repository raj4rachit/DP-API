<?php

declare(strict_types=1);

namespace Modules\V1\Doctor\Resources;

use AllowDynamicProperties;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\V1\Doctor\Models\DoctorSpecialization;
use Modules\V1\Hospital\Resources\HospitalResource;
use Modules\V1\User\Resources\UserResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="DoctorResource",
 *     title="Doctor Resource",
 *     description="Doctor resource representation",
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
#[AllowDynamicProperties] final class DoctorResource extends JsonResource
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
            'clinic_address' => $this->address,
            'contact_phone' => $this->contact_phone,
            'user' => new UserResource($this->whenLoaded('user')),
            'hospital' => new HospitalResource($this->whenLoaded('hospital')),
            'specializations' => DoctorSpecializationResource::collection($this->whenLoaded('specializations')),
        ];
    }
}