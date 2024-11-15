<?php

declare(strict_types=1);

namespace Modules\V1\Patient\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\V1\User\Models\User;

/**
 * @OA\Schema(
 *     schema="PatientCreateRequest",
 *     title="Patient Create Request",
 *     description="Request data for creating Patient profile.",
 *     type="object",
 *     required={"name", "job_title"},
 *
 *     @OA\Property(property="name", type="string", example="John", description="Patient's first name"),
 *     @OA\Property(property="job_title", type="string", example="Doe", description="Patient's job title"),
 *     @OA\Property(property="dob", type="string", format="date", example="1990-01-01", description="Patient's date of birth"),
 *     @OA\Property(property="gender", type="string", example="Male", description="Patient's gender"),
 *     @OA\Property(property="address_line_1", type="string", example="123 Main St.", description="First line of patient's address"),
 *     @OA\Property(property="address_line_2", type="string", example="Apt 4B", description="Second line of patient's address"),
 *     @OA\Property(property="city", type="string", example="New York", description="City of residence"),
 *     @OA\Property(property="state", type="string", example="NY", description="State of residence"),
 *     @OA\Property(property="country", type="string", example="USA", description="Country of residence"),
 *     @OA\Property(property="postal_code", type="string", example="10001", description="Postal code"),
 *     @OA\Property(property="primary_phone", type="string", example="123-456-7890", description="Primary contact phone number"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com", description="Patient's email address"),
 *     @OA\Property(property="marital_status", type="string", example="Single", description="Marital status of the patient"),
 *     @OA\Property(
 *         property="languages",
 *         type="array",
 *         @OA\Items(type="string", example="English"),
 *         description="Languages spoken by the patient"
 *     ),
 * )
 */

final class PatientCreateRequest extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|string|in:Male,Female,Other',
            'dob' => 'required|date',
            'address_line_1' => 'required|string',
            'address_line_2' => 'nullable|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'country' => 'required|string',
            'postal_code' => 'required|string|min:5',
            'mobile_no' => 'nullable|string|max:20',
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'id_type' => 'required|string',
            'id_number' => 'required|string',
            'arn_number' => 'required|string',
            'marital_status' => 'required|string|in:Single,Married,Divorced,Widowed',
            'primary_phone' => 'required|string|max:20',
            'secondary_phone' => 'nullable|string|max:20',
            'home_phone' => 'nullable|string|max:20',
            'work_phone' => 'nullable|string|max:20',
            'languages' => 'required|array',
            'medical_aid' => 'required|string',
            'race' => 'nullable|string',
            'ethnicity' => 'nullable|string',
            'mrn_number' => 'nullable|string',
        ];

    }
}
