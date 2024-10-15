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
 *     @OA\Property(property="first_name", type="string", example="John", description="User's first name"),
 *     @OA\Property(property="last_name", type="string", example="John", description="User's first name"),
 *     @OA\Property(property="mobile_no", type="number", example="1234567895", description="User's Phone Number"),
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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'mobile_no' => ['nullable', 'numeric', 'digits_between:5,15'],
            'profile_image' => ['nullable', 'string', 'max:2048'],
            'roles' => ['nullable', 'string','exists:roles,name'],
        ];

    }
}
