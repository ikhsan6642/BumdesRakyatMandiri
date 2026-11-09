<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class CfdZone extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'default_fee' => 'decimal:2',
    ];

    public function vendors()
    {
        return $this->hasMany(CfdVendor::class, 'preferred_zone_id');
    }

    public function tickets()
    {
        return $this->hasMany(CfdTicket::class, 'zone_id');
    }

    public function getAvailableSpotNumbers(?int $ignoreVendorId = null): Collection
    {
        $prefix = preg_replace('/^Z-/i', '', $this->code);
        $occupiedSpots = $this->vendors()
            ->where('status', 'aktif')
            ->when($ignoreVendorId, fn ($query) => $query->where('id', '!=', $ignoreVendorId))
            ->whereNotNull('spot_number')
            ->pluck('spot_number')
            ->all();

        return collect(range(1, $this->capacity_spots))
            ->map(fn (int $number) => sprintf('%s-%02d', $prefix, $number))
            ->reject(fn (string $spotNumber) => in_array($spotNumber, $occupiedSpots, true))
            ->values();
    }
}
