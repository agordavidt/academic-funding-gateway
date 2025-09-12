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
        'registration_stage',
        'payment_status',
        'application_status',
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
     * Handles Nigerian phone numbers starting with 0
     *
     * @param  string  $value
     * @return void
     */
    public function setPhoneNumberAttribute($value)
    {
        $this->attributes['phone_number'] = self::cleanPhoneNumberStatic($value);
    }

    /**
     * Static method to clean and format Nigerian phone numbers.
     * All numbers should start with 0 (Nigerian format)
     *
     * @param string $phoneNumber
     * @return string
     */
    public static function cleanPhoneNumberStatic($phoneNumber)
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phoneNumber);

        // If it starts with +234, convert to 0 format
        if (substr($phone, 0, 3) === '234') {
            return '0' . substr($phone, 3);
        }

        // If it doesn't start with 0, add it (assuming it's a valid Nigerian number)
        if (substr($phone, 0, 1) !== '0' && strlen($phone) === 10) {
            return '0' . $phone;
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
     * Check if user has a valid Nigerian phone number
     */
    public function hasValidPhoneNumber()
    {
        if (!$this->phone_number) {
            return false;
        }

        // Nigerian phone numbers start with 0 and have 11 digits
        return preg_match('/^0[789][0-9]{9}$/', $this->phone_number);
    }

    /**
     * Relationships
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
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
}