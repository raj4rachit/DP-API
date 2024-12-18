<?php

declare(strict_types=1);

namespace Modules\V1\User\Models;

use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Modules\V1\Auth\Notifications\ResetPassword;
use Modules\V1\Auth\Notifications\VerifyEmailAddress;
use Modules\V1\Doctor\Models\Doctor;
use Modules\V1\Patient\Models\Patient;
use Shared\Helpers\GlobalHelper;
use Spatie\Permission\Traits\HasRoles;

final class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, HasRoles, HasUuids, Notifiable;

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'U';

    public string $prefix = 'HOA';

    protected $primaryKey = 'uuid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'provider_id',
        'provider_type',
        'oauth',
        'profile_image',
        'user_type',
        'mobile_no'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public $timestamps = false;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            //            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the currently authenticated user.
     */
    public static function active(): ?\Illuminate\Contracts\Auth\Authenticatable
    {
        return Auth::user();
    }

    /**
     * Create a verification token with an optional expiry time.
     *
     * @param  int  $hours  The number of hours until the token expires (default is 24 hours).
     * @return string The encrypted verification token.
     */
    public function createVerificationToken(int $hours = 24): string
    {
        // Generate a unique verification token
        $token = Str::random(64); // Adjust the length as needed

        // Set token expiry time (e.g., 24 hours from now)
        $expiry = Carbon::now()->addHours($hours);

        // Store the token and expiry timestamp in the database
        $this->verification_token = $token;
        $this->verification_token_expiry = $expiry;
        $this->save();

        return GlobalHelper::encrypt($token);
    }

    public function sendEmailVerificationNotification(): void
    {
        $verificationToken = $this->createVerificationToken();
        $frontEndLink = config('constants.verify_email_url');
        $link = $frontEndLink . "?token={$verificationToken}";

        $this->notify(new VerifyEmailAddress($this, $link));
    }

    public function sendPasswordResetNotification($token = ''): void
    {
        $token = $this->createVerificationToken();
        $frontEndLink = config('constants.reset_password_url');
        $link = $frontEndLink . "?token={$token}";

        $this->notify(new ResetPassword($this, $link));

    }

    public function markEmailAsVerified(): bool
    {
        $this->email_verified_at = Carbon::now();
        $this->verification_token = null;
        $this->verification_token_expiry = null;

        return $this->save();
    }

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'model_has_roles', 'model_uuid', 'role_id')->with('permissions');
    }

    // Define the relationship to Patient (One-to-One)
    public function patient()
    {
        return $this->hasOne(Patient::class, 'user_id', 'uuid');
    }

    // Define the relationship to Doctor (One-to-One)
    public function doctor()
    {
        return $this->hasOne(Doctor::class, 'user_id', 'uuid');
    }
}
