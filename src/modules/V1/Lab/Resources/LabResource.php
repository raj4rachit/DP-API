<?php

declare(strict_types=1);

namespace Modules\V1\Lab\Resources;

use AllowDynamicProperties;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\V1\Report\Resources\ReportResource;
use Modules\V1\User\Resources\UserResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="LabResource",
 *     title="Lab Resource",
 *     description="Lab resource representation",
 *
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="address", type="string", example="john@example.com"),
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
#[AllowDynamicProperties] final class LabResource extends JsonResource
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
            'address_line_1' => $this->address_line_1,
            'address_line_2' => $this->address_line_2,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'postal_code' => $this->postal_code,
            'phone' => $this->phone,
            'user' => new UserResource($this->whenLoaded('user')),
            'reports' => ReportResource::collection($this->whenLoaded('reports')),
        ];
    }
}
