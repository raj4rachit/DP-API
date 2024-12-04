<?php

declare(strict_types=1);

namespace Modules\V1\Doctor\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\V1\User\Models\User;

/**
 * @OA\Schema(
 *     schema="DoctorCreateRequest",
 *     title="Doctor Create Request",
 *     description="Request data for creating a Doctor profile.",
 *     type="object",
 *     required={"first_name", "last_name", "gender", "dob", "address_line_1", "city", "state", "country", "postal_code", "email", "marital_status", "hospital_id", "specialization"},
 *
 *     @OA\Property(property="first_name", type="string", example="John", description="Doctor's first name"),
 *     @OA\Property(property="last_name", type="string", example="Doe", description="Doctor's last name"),
 *     @OA\Property(property="gender", type="string", example="Male", description="Doctor's gender"),
 *     @OA\Property(property="dob", type="string", format="date", example="1980-12-12", description="Doctor's date of birth"),
 *     @OA\Property(property="address_line_1", type="string", example="123 Main Street", description="Doctor's primary address line"),
 *     @OA\Property(property="address_line_2", type="string", example="Apt 101", description="Optional secondary address line"),
 *     @OA\Property(property="city", type="string", example="New York", description="City where the doctor resides"),
 *     @OA\Property(property="state", type="string", example="NY", description="State where the doctor resides"),
 *     @OA\Property(property="country", type="string", example="USA", description="Country where the doctor resides"),
 *     @OA\Property(property="postal_code", type="string", example="10001", description="Postal code for the doctor's address"),
 *     @OA\Property(property="mobile_no", type="string", example="123-456-7890", description="Doctor's mobile number (optional)"),
 *     @OA\Property(property="email", type="string", format="email", example="john.doe@example.com", description="Doctor's email address"),
 *     @OA\Property(property="marital_status", type="string", example="Single", description="Doctor's marital status", enum={"Single", "Married", "Divorced", "Widowed"}),
 *     @OA\Property(property="contact_phone", type="string", example="987-654-3210", description="Doctor's contact phone number (optional)"),
 *     @OA\Property(property="hospital_id", type="string", example="9d445a1c-cee5-4a68-b729-9edf8df71d87", description="UUID of the hospital the doctor is affiliated with"),
 *     @OA\Property(property="specialization", type="array", description="Array of UUIDs representing doctor's specialization(s)",
 *         @OA\Items(type="string", example="9d445a1c-cee5-4a68-b729-9edf8df71d87")
 *     ),
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
            'address_line_1' => 'required|string',
            'address_line_2' => 'nullable|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'country' => 'required|string',
            'postal_code' => 'required|string|min:5',
            'mobile_no' => 'nullable|string|max:20',
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'marital_status' => 'required|string|in:Single,Married,Divorced,Widowed',
            'contact_phone' => 'nullable|string|max:20',
            'hospital_id' => 'required|string|exists:hospitals,uuid',
            'specialization' => 'required|array|exists:doctor_specializations,uuid',
        ];

    }
}
