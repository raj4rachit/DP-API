<?php

declare(strict_types=1);

namespace Modules\V1\Lab\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Modules\V1\Report\Models\Report;
use Modules\V1\User\Models\User;

final class Lab extends Model
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

    protected $dateFormat = 'U';

    public string $prefix = 'HOA';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'address',
    ];

    public $timestamps = false;

    // Define the relationship to User (One-to-One)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'uuid');
    }

    // Define the relationship to Medical History (One-to-Many)
    public function reports()
    {
        return $this->belongsToMany(Report::class, 'lab_reports', 'lab_id', 'report_id');
    }
}
