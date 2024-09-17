<?php

declare(strict_types=1);

namespace Modules\V1\User\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="RoleUpdateRequest",
 *     title="Role Update Request",
 *     description="Request data for updating role.",
 *     type="object",
 *     required={"name", "admin"},
 *
 *     @OA\Property(property="name", type="string", example="Admin", description="Role's name"),
 * )
 */
final class RoleUpdateRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:55'],
        ];
    }
}
