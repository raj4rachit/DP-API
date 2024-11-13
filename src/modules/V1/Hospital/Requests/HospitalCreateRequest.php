<?php

declare(strict_types=1);

namespace Modules\V1\Hospital\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\V1\Hospital\Models\Hospital;

/**
 * @OA\Schema(
 *     schema="HospitalCreateRequest",
 *     title="Hospital Create Request",
 *     description="Request data for creating Hospital profile.",
 *     type="object",
 *     required={"name"},
 *
 *     @OA\Property(property="name", type="string", example="My company", description="Hospital name"),
 *     @OA\Property(property="status", type="string", example="Active", description="Hospital status"),
 * )
 */
final class HospitalCreateRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:155', 'unique:' . Hospital::class],
            'status' => 'nullable|string|in:Active,Inactive',
            'address_line_1' => 'required|string',
            'address_line_2' => 'nullable|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'country' => 'required|string',
            'postal_code' => 'required|string|min:5',
            'phone' => 'required|string',
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . Hospital::class],
            'description' => 'nullable|string',
        ];

    }
}
