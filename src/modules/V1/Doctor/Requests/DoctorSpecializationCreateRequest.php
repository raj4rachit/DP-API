<?php

declare(strict_types=1);

namespace Modules\V1\Doctor\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\V1\Doctor\Models\DoctorSpecialization;
use Modules\V1\User\Models\User;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="DoctorSpecializationCreateRequest",
 *     title="Doctor Specialization Create Request",
 *     description="Request data for creating Doctor Specialization.",
 *     type="object",
 *     required={"name", "code", "descriptiion"},
 *
 *     @OA\Property(property="name", type="string", example="John", description="Doctor Specialization's name"),
 *     @OA\Property(property="code", type="string", example="John", description="Doctor Specialization's code"),
 *     @OA\Property(property="descriptiion", type="string", example="John", description="Doctor Specialization's description"),
 * )
 */
final class DoctorSpecializationCreateRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', 'unique:' . DoctorSpecialization::class],
            'code' => ['required', 'string', 'max:100', 'unique:' . DoctorSpecialization::class],
            'description' => 'required|string',
        ];

    }
}
