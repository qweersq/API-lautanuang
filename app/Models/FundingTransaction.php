<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundingTransaction extends Model
{
    use HasFactory;

    protected $table = 'funcing_transaction';

    protected $fillable = [
        'fisherman_tim_id',
        'investor_id',
        'date',
        'quantity',
        'fund_amount',
    ];
}
