<?php

declare(strict_types=1);

namespace Modules\V1\Hospital\Resources;

use AllowDynamicProperties;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="HospitalResource",
 *     title="Hospital Resource",
 *     description="Hospital resource representation",
 *
 *     @OA\Property(property="uuid", type="string", example="asdfasdfsadfsd"),
 *     @OA\Property(property="name", type="string", example="test name"),
 *     @OA\Property(property="location", type="string", example="JohnDoe"),
 *     @OA\Property(property="phone", type="string", example="1231231231"),
 *     @OA\Property(property="email",type="string", example="hos@gmail.com"),
 *     @OA\Property(property="description",type="string", example="Asddfdsfererer"),
 *     @OA\Property(property="status",type="string", example="Active"),
 * )
 */
#[AllowDynamicProperties] final class HospitalResource extends JsonResource
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
            'status' => $this->status,
            'phone' => $this->phone,
            'email' => $this->email,
            'location' => $this->location,
            'description' => $this->description
        ];
    }
}
