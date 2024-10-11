<?php

declare(strict_types=1);

namespace Modules\V1\User\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\V1\User\Models\Permission;

/**
 * @OA\Schema(
 *     schema="PermissionUpdateRequest",
 *     title="Permission Update Request",
 *     description="Request data for updating Permission.",
 *     type="object",
 *     required={"name", "admin"},
 *
 *     @OA\Property(property="name", type="string", example="Admin", description="Permission's name"),
 * )
 */
final class PermissionUpdateRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:55', 'unique:' . Permission::class],
            //'name' => ['required', 'string', 'max:55', Rule::unique(Permission::class)->ignore($this->route('id'),'uuid')],
        ];
    }
}
