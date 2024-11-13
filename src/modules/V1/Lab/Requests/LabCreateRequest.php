<?php

declare(strict_types=1);

namespace Modules\V1\Lab\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\V1\User\Models\User;

/**
 * @OA\Schema(
 *     schema="LabCreateRequest",
 *     title="Lab Create Request",
 *     description="Request data for creating Lab profile.",
 *     type="object",
 *     required={"name", "name"},
 *
 *     @OA\Property(property="name", type="string", example="John", description="Lab's first name"),
 *     @OA\Property(property="address", type="string", example="Doe", description="Lab's job title"),
 * )
 */
final class LabCreateRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'address_line_1' => 'required|string',
            'address_line_2' => 'nullable|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'country' => 'required|string',
            'postal_code' => 'required|string|min:5',
            'phone' => 'nullable|string|max:20',
            'user_id' => 'required|string|exists:users,uuid',
            'reports' => 'required|array|exists:reports,uuid',
        ];

    }
}
