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
 *     required={"first_name", "last_name", "email", "password"},
 *
 *     @OA\Property(property="first_name", type="string", maxLength=255, example="John"),
 *     @OA\Property(property="last_name", type="string", maxLength=255, example="John"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="password", type="string", example="password123"),
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
            'clinic_address' => ['required_if:user_type,doctor', 'string', 'max:255'],
            'contact_phone' => ['required_if:user_type,doctor', 'string', 'max:15'],
            'doctor_email_address' => ['required_if:user_type,doctor', 'string', 'lowercase', 'email', 'max:255', 'unique:' . Doctor::class . ',email'],
        ];

    }
}
