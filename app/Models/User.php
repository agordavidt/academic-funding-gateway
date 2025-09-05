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
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'school',
        'matriculation_number',
        'application_status',
        'payment_status',
        'registration_stage',
        'email_verified_at',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Route notifications for the Africa's Talking channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForAfricasTalking($notification)
    {
        // Clean and format the phone number
        if ($this->phone_number) {
            return $this->cleanPhoneNumber($this->phone_number);
        }
        
        return null;
    }

    /**
     * Clean and format phone number for Nigerian numbers
     */
    protected function cleanPhoneNumber($phoneNumber)
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // Convert Nigerian numbers to international format
        if (substr($phone, 0, 1) === '0') {
            $phone = '+234' . substr($phone, 1);
        } elseif (substr($phone, 0, 3) === '234') {
            $phone = '+' . $phone;
        } elseif (substr($phone, 0, 4) !== '+234') {
            // Assume it's a Nigerian number without country code
            $phone = '+234' . $phone;
        }
        
        return $phone;
    }

    /**
     * Get the user's full name
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Check if user has a valid phone number for SMS
     */
    public function hasValidPhoneNumber()
    {
        if (!$this->phone_number) {
            return false;
        }

        $cleaned = $this->cleanPhoneNumber($this->phone_number);
        // Nigerian mobile numbers should be 14 characters (+234xxxxxxxxx)
        return preg_match('/^\+234[7-9][0-9]{9}$/', $cleaned);
    }

    /**
     * Relationships
     */
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

    public function trainingInstitution()
    {
        return $this->belongsTo(TrainingInstitution::class);
    }

    /**
     * Scopes
     */
    public function scopeWithValidPhone($query)
    {
        return $query->whereNotNull('phone_number')
                    ->where('phone_number', '!=', '');
    }

    public function scopeWithPaymentStatus($query, $status)
    {
        return $query->where('payment_status', $status);
    }

    public function scopeWithApplicationStatus($query, $status)
    {
        return $query->where('application_status', $status);
    }

    public function scopeFromSchool($query, $school)
    {
        return $query->where('school', 'like', "%{$school}%");
    }
}