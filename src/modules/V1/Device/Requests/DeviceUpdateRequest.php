<?php

declare(strict_types=1);

namespace Modules\V1\Device\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\V1\Device\Models\Device;

/**
 * @OA\Schema(
 *     schema="DeviceUpdateRequest",
 *     title="Device Update Request",
 *     description="Request data for update Device profile.",
 *     type="object",
 *     required={"name","device_vendor_id","status","api_key","device_type"},
 *
 *     @OA\Property(property="name", type="string", example="My company", description="Device name"),
 *     @OA\Property(property="device_vendor_id", type="string", example="safsdfsf", description="Device Vendor id"),
 *     @OA\Property(property="api_key", type="string", example="Mydsfsdfds", description="Device api key"),
 *     @OA\Property(property="device_type", type="string", example="My company", description="Device type"),
 *     @OA\Property(property="status", type="string", example="Active", description="Device status"),
 *     @OA\Property(property="device_sim", type="string", example="No", description="Device Sim"),
 *     @OA\Property(property="secret_key", type="string", example="Acsdfgfgtive", description="Device secret key"),
 *     @OA\Property(property="device_model", type="string", example="Active vcxzv", description="Device model"),
 *     @OA\Property(property="rfid", type="string", example="Yes", description="Device rfid"),
 * )
 */
final class DeviceUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('id');
        return [
            'name' => [
                'required',
                'string',
                'max:155',
                Rule::unique('devices', 'name')->ignore($id, 'uuid'), // Ensures name is unique but ignores the current device
            ],
            'device_vendor_id' => 'required|string|exists:device_vendors,uuid',
            'status' => 'required|string|in:Active,Inactive',
            'api_key' => [
                'required',
                'string',
                'max:155',
                Rule::unique('devices', 'api_key')->ignore($id, 'uuid'), // Ensures name is unique but ignores the current device
            ],
            'device_type' => 'required|string',
            'device_sim' => 'nullable|string|in:Yes,No',
            'secret_key' => 'nullable|string',
            'device_model' => 'nullable|string',
            'rfid' => 'nullable|string|in:Yes,No',
        ];

    }
}
