<?php

declare(strict_types=1);

namespace Modules\V1\User\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\V1\User\Models\Role;

/**
 * @OA\Schema(
 *     schema="RoleCreateRequest",
 *     title="Role Create Request",
 *     description="Request data for creating role.",
 *     type="object",
 *     required={"name", "admin"},
 *     required={"permissions", "test,test1,test2"},
 *
 *     @OA\Property(property="name", type="string", example="Admin", description="Role's name"),
 *     @OA\Property(property="permissions", type="array", description="Permission's name",
 *           @OA\Items(type="string")
 *       )
 * )
 */
final class RoleCreateRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:55', 'unique:' . Role::class],
            'permissions' => ['required', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ];
    }
}