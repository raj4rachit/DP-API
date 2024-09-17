<?php

declare(strict_types=1);

namespace Modules\V1\User\Resources;

use AllowDynamicProperties;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Shared\Helpers\StringHelper;

/**
 * @OA\Schema(
 *     schema="RoleResource",
 *     title="Role Resource",
 *     description="Role resource representation",
 *
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Admin")
 * )
 */
#[AllowDynamicProperties] final class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => StringHelper::toTitleCase($this->name),
        ];
    }
}
