<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PamsimasSetting extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'rate_per_m3_social' => 'decimal:2',
        'rate_per_m3_household' => 'decimal:2',
        'rate_per_m3_business' => 'decimal:2',
        'subscription_fee' => 'decimal:2',
        'late_fee' => 'decimal:2',
    ];

    public static function getActiveSetting()
    {
        return static::first() ?? static::create([
            'rate_per_m3_social' => 1500,
            'rate_per_m3_household' => 2500,
            'rate_per_m3_business' => 4000,
            'subscription_fee' => 10000,
            'late_fee' => 5000,
            'due_date_day' => 20,
            'water_source_info' => 'Sumur Bor Dalam Dusun Krajan & Sumber Mata Air Gunung',
        ]);
    }
}
