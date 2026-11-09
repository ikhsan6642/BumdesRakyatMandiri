<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PamsimasMaintenance extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'cost' => 'decimal:2',
        'expense_date' => 'date',
    ];
}
