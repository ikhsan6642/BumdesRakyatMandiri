<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PamsimasCustomer extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'installation_date' => 'date',
    ];

    public function usages()
    {
        return $this->hasMany(PamsimasUsage::class, 'customer_id');
    }

    public function latestUsage()
    {
        return $this->hasOne(PamsimasUsage::class, 'customer_id')->latestOfMany('billing_period');
    }

    public function getLastMeterReadingAttribute()
    {
        $latest = $this->latestUsage;
        return $latest ? $latest->current_meter : $this->initial_meter;
    }
}
