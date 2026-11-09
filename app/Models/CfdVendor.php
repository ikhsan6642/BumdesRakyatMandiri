<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CfdVendor extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function preferredZone()
    {
        return $this->belongsTo(CfdZone::class, 'preferred_zone_id');
    }

    public function tickets()
    {
        return $this->hasMany(CfdTicket::class, 'vendor_id');
    }
}
