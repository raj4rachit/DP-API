<?php

declare(strict_types=1);

namespace Modules\V1\User\Resources;

use AllowDynamicProperties;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Shared\Helpers\StringHelper;

/**
 * @OA\Schema(
 *     schema="PermissionResource",
 *     title="Permission Resource",
 *     description="Permission resource representation",
 *
 *     @OA\Property(property="uuid", type="string", example=1),
 *     @OA\Property(property="name", type="string", example="Admin")
 * )
 */
#[AllowDynamicProperties] final class PermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'title' => Str::title(str_replace('-', ' ', $this->name)),
            'guard_name' => $this->guard_name,
        ];
    }
}
