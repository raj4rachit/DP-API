<?php

declare(strict_types=1);

namespace Modules\V1\Device\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\V1\Device\Models\Device;
use Modules\V1\Device\Models\DeviceVendor;

/**
 * @OA\Schema(
 *     schema="DeviceCreateRequest",
 *     title="Device Create Request",
 *     description="Request data for creating Device profile.",
 *     type="object",
 *     required={"name"},
 *
 *     @OA\Property(property="name", type="string", example="My company", description="Device name"),
 *     @OA\Property(property="status", type="string", example="Active", description="Device status"),
 * )
 */
final class DeviceCreateRequest extends FormRequest
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
        return [
            'name' => ['required', 'string', 'max:155', 'unique:' . Device::class],
            'device_vendor_id' => 'required|string|exists:device_vendors,id',
            'status' => 'required|string|in:Active,Inactive',
            'api_key' => ['nullable', 'string', 'max:255', 'unique:' . Device::class],
            'device_type' => 'nullable|string',
            'device_sim' => 'nullable|string|in:Yes,No',
            'secret_key' => 'nullable|string',
            'device_model' => 'nullable|string',
            'rfid' => 'nullable|string|in:Yes,No'
        ];

    }
}
