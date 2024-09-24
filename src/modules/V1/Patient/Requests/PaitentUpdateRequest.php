<?php

declare(strict_types=1);

namespace Modules\V1\Patient\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="PaitentUpdateRequest",
 *     title="Patient Update Request",
 *     description="Request data for updating Patient profile.",
 *     type="object",
 *     required={"name", "job_title"},
 *
 *     @OA\Property(property="name", type="string", example="John", description="Patient's first name"),
 *     @OA\Property(property="job_title", type="string", example="Doe", description="Patient's job title"),
 * )
 */
final class PaitentUpdateRequest extends FormRequest
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
            'mobile_no' => ['nullable','string','max:20'],
            'gender' => ['required','string','max:50'],
            'dob' => ['required','date'],
            'profile_image' => ['nullable','string','max:2048'],
            'address' => ['required','string','max:255'],
        ];

    }
}
