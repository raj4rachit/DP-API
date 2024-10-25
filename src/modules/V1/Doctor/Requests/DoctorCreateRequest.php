<?php

declare(strict_types=1);

namespace Modules\V1\Doctor\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\V1\User\Models\User;

/**
 * @OA\Schema(
 *     schema="DoctorCreateRequest",
 *     title="Doctor Create Request",
 *     description="Request data for creating Doctor profile.",
 *     type="object",
 *     required={"name", "job_title"},
 *
 *     @OA\Property(property="first_name", type="string", example="John", description="Doctor's first name"),
 *     @OA\Property(property="last_name", type="string", example="John", description="Doctor's last name"),
 *     @OA\Property(property="gender", type="string", example="Doe", description="Doctor's gender"),
 * )
 */
final class DoctorCreateRequest extends FormRequest
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
            'clinic_address' => 'required|string',
            'mobile_no' => 'nullable|string|max:20',
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'marital_status' => 'required|string|in:Single,Married,Divorced,Widowed',
            'contact_phone' => 'nullable|string|max:20',
            'hospital_id' => 'required|string|exists:hospitals,uuid',
            'specialization' => 'required|array|exists:doctor_specializations,uuid',
        ];

    }
}
