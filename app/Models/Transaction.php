<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function businessUnit()
    {
        return $this->belongsTo(BusinessUnit::class, 'unit_code', 'code');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
