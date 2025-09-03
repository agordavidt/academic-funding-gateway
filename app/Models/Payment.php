<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_id',
        'amount',
        'status',
        'paid_at',
        'gateway_response',
        'payment_evidence',
        'payment_note',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'gateway_response' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPaymentEvidenceUrlAttribute()
    {
        if ($this->payment_evidence) {
            return asset('storage/' . $this->payment_evidence);
        }
        return null;
    }
}