<?php

declare(strict_types=1);

namespace Modules\V1\Doctor\Resources;

use AllowDynamicProperties;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\V1\User\Resources\UserResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="DoctorSpecializationResource",
 *     title="Doctor Specialization Resource",
 *     description="Doctor Specialization resource representation",
 *
 *     @OA\Property(property="name", type="string", example="Cardiology"),
 *     @OA\Property(property="description", type="string", example="Heart-related diseases and treatments"),
 *     @OA\Property(property="code", type="string", example="CAR-1234"),
 *     @OA\Property(
 *         property="doctors",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/DoctorResource"),
 *         description="List of doctors specialized in this field"
 *     )
 * )
 */

#[AllowDynamicProperties] final class DoctorSpecializationResource extends JsonResource
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
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'doctors' => new DoctorResource($this->whenLoaded('doctor')),
        ];
    }
}
