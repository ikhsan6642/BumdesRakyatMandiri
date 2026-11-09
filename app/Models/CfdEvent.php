<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CfdEvent extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'event_date' => 'date',
        'total_stall_revenue' => 'decimal:2',
        'parking_revenue' => 'decimal:2',
        'cleanliness_cost' => 'decimal:2',
        'security_cost' => 'decimal:2',
        'sound_electricity_cost' => 'decimal:2',
        'net_revenue' => 'decimal:2',
    ];

    public function tickets()
    {
        return $this->hasMany(CfdTicket::class, 'event_id');
    }

    public function getTotalGrossRevenueAttribute()
    {
        return $this->total_stall_revenue + $this->parking_revenue;
    }

    public function getTotalOperationalCostAttribute()
    {
        return $this->cleanliness_cost + $this->security_cost + $this->sound_electricity_cost;
    }

    public function calculateNetRevenue()
    {
        $this->total_stall_revenue = CfdTicket::successfullyRecorded()
            ->where('event_id', $this->id)
            ->where('payment_status', 'lunas')
            ->sum('total_amount');
        $this->net_revenue = ($this->total_stall_revenue + $this->parking_revenue) - $this->total_operational_cost;
        $this->total_stalls_occupied = $this->tickets()->count();
        $this->save();

        return $this->net_revenue;
    }

    public function syncStatusToSchedule(): bool
    {
        if ($this->status === 'selesai') {
            return false;
        }

        $start = Carbon::parse(
            $this->event_date->format('Y-m-d').' '.$this->collection_start_time,
            config('app.timezone')
        );
        $end = Carbon::parse(
            $this->event_date->format('Y-m-d').' '.$this->collection_end_time,
            config('app.timezone')
        );
        $now = Carbon::now(config('app.timezone'));

        $scheduledStatus = $now->lt($start)
            ? 'terjadwal'
            : ($now->lt($end) ? 'berlangsung' : 'selesai');

        if ($this->status === $scheduledStatus) {
            return false;
        }

        $this->status = $scheduledStatus;
        $this->save();

        return true;
    }
}
