<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'phone_number', 'email', 'first_name', 'last_name', 'address',
        'school', 'matriculation_number', 'state_of_origin', 'lga',
        'date_of_birth', 'gender', 'bank_name', 'account_number',
        'account_name', 'passport_photo', 'profile_completion_status',
        'payment_status', 'application_status', 'profile_completed_at',
        'payment_completed_at', 'application_submitted_at'
    ];

    protected $hidden = ['remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'profile_completed_at' => 'datetime',
        'payment_completed_at' => 'datetime',
        'application_submitted_at' => 'datetime',
        'date_of_birth' => 'date',
        'password' => 'hashed',
    ];

    // Relationships
    public function application()
    {
        return $this->hasOne(Application::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function latestPayment()
    {
        return $this->hasOne(Payment::class)->latest();
    }

    // Scopes
    public function scopeWithPaidFees($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopeWithCompletedProfile($query)
    {
        return $query->where('profile_completion_status', 'completed');
    }

    public function scopeWithApplicationStatus($query, $status)
    {
        return $query->where('application_status', $status);
    }

    // Accessors & Mutators
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function setPhoneNumberAttribute($value)
    {
        $this->attributes['phone_number'] = preg_replace('/[^0-9+]/', '', $value);
    }

    // Helper Methods
    public function hasCompletedProfile(): bool
    {
        return $this->profile_completion_status === 'completed';
    }

    public function hasPaidFee(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function hasSubmittedApplication(): bool
    {
        return $this->application_status !== 'not_started';
    }

    public function canSubmitApplication(): bool
    {
        return $this->hasCompletedProfile() && $this->hasPaidFee();
    }

    public function getProgressPercentage(): int
    {
        $steps = [
            $this->hasCompletedProfile(),
            $this->hasPaidFee(),
            $this->hasSubmittedApplication()
        ];
        
        return (int) ((array_sum($steps) / count($steps)) * 100);
    }
}
