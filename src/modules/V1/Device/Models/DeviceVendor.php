<?php

declare(strict_types=1);

namespace Modules\V1\Device\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

final class DeviceVendor extends Model
{
    use HasFactory, HasUuids, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // Set the primary key to 'uuid'
    protected $primaryKey = 'uuid';

    protected $fillable = [
        'name',
        'status',
    ];

    public $timestamps = false;

    public function device(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Device::class, 'device_vendor_id', 'uuid');
    }
}
