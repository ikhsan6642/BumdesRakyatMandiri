<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PamsimasUsage extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'rate_per_m3' => 'decimal:2',
        'water_charge' => 'decimal:2',
        'subscription_fee' => 'decimal:2',
        'late_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'reading_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(PamsimasCustomer::class, 'customer_id');
    }
}
