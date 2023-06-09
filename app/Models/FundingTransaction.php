<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundingTransaction extends Model
{
    use HasFactory;

    protected $table = 'funding_transaction';

    protected $fillable = [
        'fisherman_tim_id',
        'investors_id',
        'date',
        'quantity',
        'fund_amount',
    ];

    public function investors()
    {
        return $this->belongsTo(Investors::class);
    }

    public function fisherman_tim()
    {
        return $this->belongsTo(FishermanTim::class);
    }
}
