<?php

declare(strict_types=1);

namespace Modules\V1\Hospital\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Modules\V1\Doctor\Models\Doctor;

final class Hospital extends Model
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
        'name', 'address_line_1', 'address_line_2', 'city', 'state', 'country', 'postal_code', 'phone', 'email', 'description', 'status',
    ];

    public $timestamps = false;

    // Define the relationship to Doctor (One-to-One)
    public function doctor()
    {
        return $this->hasMany(Doctor::class, 'hospital_id', 'uuid');
    }
}
