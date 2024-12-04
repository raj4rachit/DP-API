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
     * @OA\Schema(
     *     schema="HospitalCreateRequest",
     *     title="Hospital Create Request",
     *     description="Request body for creating or updating a hospital",
     *     type="object",
     *     required={"name", "address_line_1", "city", "state", "country", "postal_code", "phone", "email"},
     *
     *     @OA\Property(property="name", type="string", example="City Hospital", description="Name of the hospital"),
     *     @OA\Property(property="status", type="string", example="Active", description="Status of the hospital. Can be either 'Active' or 'Inactive'"),
     *     @OA\Property(property="address_line_1", type="string", example="123 Main St", description="Primary address line"),
     *     @OA\Property(property="address_line_2", type="string", example="Apt 101", description="Secondary address line (optional)"),
     *     @OA\Property(property="city", type="string", example="Metropolis", description="City where the hospital is located"),
     *     @OA\Property(property="state", type="string", example="StateName", description="State where the hospital is located"),
     *     @OA\Property(property="country", type="string", example="CountryName", description="Country where the hospital is located"),
     *     @OA\Property(property="postal_code", type="string", example="12345", description="Postal code of the hospital's location"),
     *     @OA\Property(property="phone", type="string", example="+1-800-123-4567", description="Phone number of the hospital"),
     *     @OA\Property(property="email", type="string", example="contact@cityhospital.com", description="Contact email address"),
     *     @OA\Property(property="description", type="string", example="A leading healthcare provider in the city", description="A short description of the hospital (optional)")
     * )
     */

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
