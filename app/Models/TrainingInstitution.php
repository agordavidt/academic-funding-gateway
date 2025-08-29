<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingInstitution extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'contact_email', 'contact_phone',
        'address', 'website', 'status', 'programs_offered', 'max_grant_amount'
    ];

    protected $casts = [
        'programs_offered' => 'array',
        'max_grant_amount' => 'decimal:2'
    ];

    // Relationships
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Helper Methods
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}

