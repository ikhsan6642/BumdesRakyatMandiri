<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessUnit extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'unit_code', 'code');
    }

    public function getTotalIncomeAttribute()
    {
        return $this->transactions()->where('type', 'income')->sum('amount');
    }

    public function getTotalExpenseAttribute()
    {
        return $this->transactions()->where('type', 'expense')->sum('amount');
    }

    public function getNetProfitAttribute()
    {
        return $this->total_income - $this->total_expense;
    }
}
