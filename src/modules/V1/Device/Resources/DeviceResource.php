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
 *     schema="DeviceResource",
 *     title="Device Resource",
 *     description="Device resource representation",
 *
 *     @OA\Property(property="uuid", type="string", example=1),
 *     @OA\Property(property="device_vendor_id",type="string", example="fasdfsdaf"),
 *     @OA\Property(property="api_key", type="string", example="JohnDoe"),
 *     @OA\Property(property="device_type", type="string", example="test"),
 *     @OA\Property(property="device_sim",type="string", example="Yes"),
 *     @OA\Property(property="secret_key",type="string", example="Asddfdsfererer"),
 *     @OA\Property(property="device_model",type="string", example="open model"),
 *     @OA\Property(property="rfid",type="string", example="No"),
 *     @OA\Property(property="status",type="string", example="Active"),
 * )
 */
#[AllowDynamicProperties] final class DeviceResource extends JsonResource
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
            'api_key' => $this->api_key,
            'device_type' => $this->device_type,
            'device_sim' => $this->device_sim,
            'secret_key' => $this->secret_key,
            'device_model' => $this->device_model,
            'rfid' => $this->rfid,
            'device_vendor' => new DeviceVendorResource($this->whenLoaded('vendor')),
        ];
    }
}
