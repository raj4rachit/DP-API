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
 *     @OA\Property(property="uuid", type="string", example="asdfasdfsadfsd"),
 *     @OA\Property(property="name", type="string", example="test name"),
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
            'name' => $this->name,
            'status' => $this->status,
            'api_key' => $this->api_key,
            'device_type' => $this->device_type,
            'device_sim' => $this->device_sim,
            'secret_key' => $this->secret_key,
            'device_model' => $this->device_model,
            'rfid' => $this->rfid,
            'sensor_code' => $this->sensor_code,
            'image' => $this->image,
            'up_front_cost' => $this->up_front_cost,
            'shipping_cost' => $this->shipping_cost,
            'monthly_cost' => $this->monthly_cost,
            'sensor_id_required' => $this->sensor_id_required,
            'in_stock' => $this->in_stock,
            'virtual' => $this->virtual,
            'deprecated' => $this->deprecated,
            'device_vendor' => new DeviceVendorResource($this->whenLoaded('vendor')),
        ];
    }
}
