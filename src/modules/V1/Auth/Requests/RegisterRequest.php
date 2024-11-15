<?php

declare(strict_types=1);

namespace Modules\V1\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;
use Modules\V1\Doctor\Models\Doctor;
use Modules\V1\User\Models\User;

/**
 * @OA\Schema(
 *     schema="RegisterRequest",
 *     title="User Registration Request",
 *     description="Schema for the user registration request",
 *     type="object",
 *     required={"first_name", "last_name", "email", "password", "user_type"},
 *
 *     @OA\Property(
 *         property="first_name",
 *         type="string",
 *         maxLength=255,
 *         example="John",
 *         description="The user's first name"
 *     ),
 *     @OA\Property(
 *         property="last_name",
 *         type="string",
 *         maxLength=255,
 *         example="Doe",
 *         description="The user's last name"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         example="john@example.com",
 *         description="The user's email address"
 *     ),
 *     @OA\Property(
 *         property="password",
 *         type="string",
 *         example="password123",
 *         description="The user's password"
 *     ),
 *     @OA\Property(
 *         property="user_type",
 *         type="string",
 *         enum={"doctor", "patient", "lab"},
 *         example="doctor",
 *         description="The type of the user (doctor, patient, or lab)"
 *     ),
 *     @OA\Property(
 *         property="address_line_1",
 *         type="string",
 *         maxLength=255,
 *         example="123 Doctor St.",
 *         description="The user's primary address line (required for doctors and labs)"
 *     ),
 *     @OA\Property(
 *         property="address_line_2",
 *         type="string",
 *         maxLength=255,
 *         example="Suite 10",
 *         description="The user's secondary address line (optional)"
 *     ),
 *     @OA\Property(
 *         property="city",
 *         type="string",
 *         maxLength=155,
 *         example="New York",
 *         description="The city of the user's address (required for doctors and labs)"
 *     ),
 *     @OA\Property(
 *         property="state",
 *         type="string",
 *         maxLength=155,
 *         example="NY",
 *         description="The state of the user's address (required for doctors and labs)"
 *     ),
 *     @OA\Property(
 *         property="country",
 *         type="string",
 *         maxLength=155,
 *         example="USA",
 *         description="The country of the user's address (required for doctors and labs)"
 *     ),
 *     @OA\Property(
 *         property="postal_code",
 *         type="string",
 *         minLength=5,
 *         example="10001",
 *         description="The postal code of the user's address (required for doctors and labs)"
 *     ),
 *     @OA\Property(
 *         property="contact_phone",
 *         type="string",
 *         maxLength=15,
 *         example="1231231234",
 *         description="The phone number of the doctor (required for doctor users)"
 *     ),
 *     @OA\Property(
 *         property="doctor_email_address",
 *         type="string",
 *         format="email",
 *         example="doctor@example.com",
 *         description="The doctor's email address (required for doctor users)"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         maxLength=155,
 *         example="LabCorp",
 *         description="The name of the lab (required for lab users)"
 *     ),
 *     @OA\Property(
 *         property="reports",
 *         type="array",
 *         @OA\Items(type="string", example="report-uuid-123"),
 *         description="Array of UUIDs of reports (required for lab users)"
 *     )
 * )
 */

final class RegisterRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', Rules\Password::defaults()],
            'user_type' => ['required', 'string', 'in:doctor,patient,lab'],
            'address_line_1' => ['required_if:user_type,doctor,lab', 'string', 'max:255'],
            'address_line_2' => ['nullable', 'string', 'max:255'],
            'city' => ['required_if:user_type,doctor,lab', 'string', 'max:155'],
            'state' => ['required_if:user_type,doctor,lab', 'string', 'max:155'],
            'country' => ['required_if:user_type,doctor,lab', 'string', 'max:155'],
            'postal_code' => ['required_if:user_type,doctor,lab', 'string', 'min:5'],
            'contact_phone' => ['required_if:user_type,doctor', 'string', 'max:15'],
            'doctor_email_address' => ['required_if:user_type,doctor', 'string', 'lowercase', 'email', 'max:255', 'unique:' . Doctor::class . ',email'],
            'name' => ['required_if:user_type,lab', 'string', 'max:155'],
            'reports' => ['required_if:user_type,lab', 'array', 'exists:reports,uuid'],
        ];

    }
}
