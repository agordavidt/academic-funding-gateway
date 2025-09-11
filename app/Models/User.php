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
     * Mutator to clean and format the phone number before saving.
     * This handles both manual and imported data.
     *
     * @param  string  $value
     * @return void
     */
    public function setPhoneNumberAttribute($value)
    {
        $this->attributes['phone_number'] = self::cleanPhoneNumberStatic($value);
    }
    
    /**
     * Route notifications for the Africa's Talking channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForAfricasTalking($notification)
    {
        // The phone number is already clean due to the mutator
        return $this->phone_number;
    }

    /**
     * Static method to clean and format phone numbers.
     *
     * @param string $phoneNumber
     * @return string
     */
    public static function cleanPhoneNumberStatic($phoneNumber)
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Check if the number is already in the international format
        if (substr($phone, 0, 4) === '+234') {
            return $phone; // It's already correctly formatted
        }

        // Add the country code if it starts with '0'
        if (substr($phone, 0, 1) === '0') {
            return '+234' . substr($phone, 1);
        }
        
        // Add the country code if it starts with '234'
        if (substr($phone, 0, 3) === '234') {
            return '+' . $phone;
        }

        // If it's a 10-digit number without a leading zero, assume it's a Nigerian number and add +234
        if (strlen($phone) === 10) {
            return '+234' . $phone;
        }

        return $phone; // Return as-is if none of the patterns match
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