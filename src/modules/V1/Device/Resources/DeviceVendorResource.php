<?php

declare(strict_types=1);

namespace Modules\V1\Device\Resources;

use AllowDynamicProperties;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\V1\User\Resources\UserResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="DeviceVendorResource",
 *     title="Device Vendor Resource",
 *     description="Device Vendor resource representation",
 *
 *     @OA\Property(property="uuid", type="string", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="status",type="string", example="Active"),
 * )
 */
#[AllowDynamicProperties] final class DeviceVendorResource extends JsonResource
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
            'status' => $this->status,
            'name' => $this->name,
        ];
    }
}