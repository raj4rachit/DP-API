<?php

declare(strict_types=1);

namespace Modules\V1\User\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="UserUpdateRequest",
 *     title="Profile Update Request",
 *     description="Request data for updating user profile.",
 *     type="object",
 *     required={"name", "gender", "dob", "address"},
 *
 *     @OA\Property(property="name", type="string", example="John", description="User's first name"),
 *     @OA\Property(property="mobile_no", type="number", example="1234567895", description="User's Phone Number"),
 *     @OA\Property(property="gender", type="string", example="male", description="User's gender"),
 *     @OA\Property(property="dob", type="string", example="2002-04-22", description="User's date of birth"),
 *     @OA\Property(property="address", type="string", example="Test address, Ahmedabad", description="User's address"),
 *     @OA\Property(property="profile_image", type="string", example="", description="User's image"),
 * )
 */
final class UserUpdateRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'mobile_no' => ['nullable', 'numeric', 'digits_between:5,15'],
            'gender' => ['required', 'string', 'in:male,female'],
            'dob' => ['required', 'date'],
            'profile_image' => ['nullable', 'string', 'max:2048'],
            'address' => ['required', 'string', 'max:255'],
            'roles' => ['nullable', 'string','exists:roles,name'],
        ];

    }
}
