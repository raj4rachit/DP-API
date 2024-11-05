<?php

declare(strict_types=1);

namespace Modules\V1\Package\Resources;

use AllowDynamicProperties;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="PackageResource",
 *     title="Package Resource",
 *     description="Package resource representation",
 *
 *     @OA\Property(property="uuid", type="string", example="asdfasdfsadfsd"),
 *     @OA\Property(property="name", type="string", example="test name"),
 *     @OA\Property(property="description",type="string", example="Asddfdsfererer"),
 *     @OA\Property(property="total_patients",type="string", example="10"),
 *     @OA\Property(property="patient_charge",type="string", example="100"),
 *     @OA\Property(property="is_default",type="string", example="1"),
 *     @OA\Property(property="status",type="string", example="Active"),
 * )
 */
#[AllowDynamicProperties] final class PackageResource extends JsonResource
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
            'description' => $this->description,
            'total_patients' => $this->total_patients,
            'patient_charge' => $this->patient_charge,
            'is_default' => $this->is_default,
            'status' => $this->status,
        ];
    }
}
