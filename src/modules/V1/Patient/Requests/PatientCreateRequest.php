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
            'address' => 'required|string',
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
