<?php

declare(strict_types=1);

namespace Modules\V1\Device\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\V1\Device\Models\DeviceVendor;

/**
 * @OA\Schema(
 *     schema="DeviceVendorCreateRequest",
 *     title="Device Vendor Create Request",
 *     description="Request data for creating Device Vendor profile.",
 *     type="object",
 *     required={"name"},
 *
 *     @OA\Property(property="name", type="string", example="My company", description="Device Vendor's company name"),
 *     @OA\Property(property="status", type="string", example="Active", description="Device Vendor status"),
 * )
 */
final class DeviceVendorCreateRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:55', 'unique:' . DeviceVendor::class],
            'status' => 'nullable|string|in:Active,Inactive',
        ];

    }
}
