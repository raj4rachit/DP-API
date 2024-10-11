<?php

declare(strict_types=1);

namespace Modules\V1\User\Models;

use Database\Factories\RoleFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\Traits\HasPermissions;

final class Role extends SpatieRole
{
    use HasFactory, HasUuids, HasPermissions;

    protected $primaryKey = 'uuid';

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'U';

    protected $fillable = [
        'name','gaurd_name'
    ];

    public $timestamps = false;

    protected static function newFactory(): RoleFactory
    {
        return RoleFactory::new();
    }

    public function users(): BelongsToMany
    {
        return $this->morphedByMany(User::class,'model', 'model_has_roles', 'role_id', 'model_uuid'); // Adjust User class as needed
    }

    public function usersCount()
    {
        return $this->users()->count();
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            config('permission.models.permission'),
            config('permission.table_names.role_has_permissions'),
            'role_id',
            'permission_id'
        );
    }


}
