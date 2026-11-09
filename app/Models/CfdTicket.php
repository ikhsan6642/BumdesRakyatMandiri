<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CfdTicket extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'fee_amount' => 'decimal:2',
        'electricity_fee' => 'decimal:2',
        'cleanliness_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(CfdEvent::class, 'event_id');
    }

    public function vendor()
    {
        return $this->belongsTo(CfdVendor::class, 'vendor_id');
    }

    public function zone()
    {
        return $this->belongsTo(CfdZone::class, 'zone_id');
    }

    public function scopeSuccessfullyRecorded($query)
    {
        return $query->where(function ($query) {
            $query->where('payment_status', '!=', 'lunas')
                ->orWhere(function ($query) {
                    $query->whereExists(function ($query) {
                        $query->selectRaw('1')
                            ->from('transactions')
                            ->whereColumn('transactions.reference_id', 'cfd_tickets.id')
                            ->where('transactions.reference_type', 'cfd')
                            ->where('transactions.type', 'income');
                    })->orWhereIn('ticket_number', function ($query) {
                        $query->select('receipt_number')
                            ->from('transactions')
                            ->where('unit_code', 'cfd')
                            ->where('type', 'income')
                            ->whereNotNull('receipt_number');
                    });
                });
        });
    }
}
