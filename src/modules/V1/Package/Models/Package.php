<?php

declare(strict_types=1);

namespace Modules\V1\Package\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Modules\V1\Subscription\Models\Subscription;

final class Package extends Model
{
    use HasFactory, HasUuids, Notifiable;

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    // Set the primary key to 'uuid'
    protected $primaryKey = 'uuid';

    // If the UUID is not auto-incrementing, set this to false
    public $incrementing = false;

    // Specify the type of the primary key
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'description', 'total_patients', 'patient_charge', 'is_default', 'status',
    ];

    public $timestamps = false;

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
