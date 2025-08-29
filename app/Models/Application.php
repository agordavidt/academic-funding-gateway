<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'training_institution_id', 'need_assessment_text',
        'supporting_documents', 'terms_agreed_at', 'admin_notes',
        'rejection_reason', 'reviewed_at', 'reviewed_by', 'approved_amount'
    ];

    protected $casts = [
        'supporting_documents' => 'array',
        'terms_agreed_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'approved_amount' => 'decimal:2'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trainingInstitution()
    {
        return $this->belongsTo(TrainingInstitution::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->whereHas('user', function($q) {
            $q->where('application_status', 'pending');
        });
    }

    public function scopeReviewing($query)
    {
        return $query->whereHas('user', function($q) {
            $q->where('application_status', 'reviewing');
        });
    }

    public function scopeAccepted($query)
    {
        return $query->whereHas('user', function($q) {
            $q->where('application_status', 'accepted');
        });
    }

    public function scopeRejected($query)
    {
        return $query->whereHas('user', function($q) {
            $q->where('application_status', 'rejected');
        });
    }
}

