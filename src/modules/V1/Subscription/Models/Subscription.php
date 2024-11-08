<?php

declare(strict_types=1);

namespace Modules\V1\Subscription\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Modules\V1\Doctor\Models\Doctor;
use Modules\V1\Package\Models\Package;
use Modules\V1\User\Models\User;

final class Subscription extends Model
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
        'name', 'patient_charge', 'patient_count', 'amount', 'start_date', 'end_date', 'payment_transaction_id', 'user_id', 'package_id',
    ];

    public $timestamps = false;


    public function subscribable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'user_id', 'user_id');
    }
}
