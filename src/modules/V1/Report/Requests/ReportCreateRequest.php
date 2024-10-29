<?php

declare(strict_types=1);

namespace Modules\V1\Report\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\V1\Report\Models\Report;

/**
 * @OA\Schema(
 *     schema="ReportCreateRequest",
 *     title="Report Create Request",
 *     description="Request data for creating Report profile.",
 *     type="object",
 *     required={"name"},
 *
 *     @OA\Property(property="name", type="string", example="My company", description="Report name"),
 *     @OA\Property(property="description", type="string", example="Active", description="Report description"),
 * )
 */
final class ReportCreateRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:155', 'unique:' . Report::class],
            'description' => 'nullable|string',
        ];

    }
}
