<?php

declare(strict_types=1);

namespace Modules\V1\Package\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\V1\Package\Models\Package;

/**
 * @OA\Schema(
 *     schema="PackageCreateRequest",
 *     title="Package Create Request",
 *     description="Request data for creating Package profile.",
 *     type="object",
 *     required={"name","total_patients","patient_charge","is_default","status"},
 *
 *     @OA\Property(property="name", type="string", example="My company", description="Package name"),
 *     @OA\Property(property="description",type="string", example="Asddfdsfererer"),
 *     @OA\Property(property="total_patients",type="string", example="10"),
 *     @OA\Property(property="patient_charge",type="string", example="100"),
 *     @OA\Property(property="is_default",type="string", example="1"),
 *     @OA\Property(property="status",type="string", example="Active"),
 * )
 */
final class PackageCreateRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:155', 'unique:' . Package::class],
            'description' => 'nullable|string',
            'total_patients' => 'required|numeric',
            'patient_charge' => 'required|numeric',
            'is_default' => 'required|numeric|in:0,1',
            'status' => 'required|string|in:Active,Inactive,Canceled',
        ];

    }
}
