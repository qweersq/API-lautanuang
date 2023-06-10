<?php

namespace App\Models;

use App\Models\Location;
use App\Models\FishermanCatch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class FishermanTim extends Model
{
    use HasFactory;

    protected $table = 'fisherman_tim';

    protected $fillable = [
        'name',
        'phone',
        'year_formed',
        'address',
        'balance',
        'location_id',
        'quantity',
        'total_assets',
        'divident_yield',
        'debt_to_equity_ratio',
        'market_cap'
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function fisherman()
    {
        return $this->hasMany(Fisherman::class, 'tim_id');
    }

    public function fishermanCatch()
    {
        return $this->hasMany(FishermanCatch::class, 'fisherman_tim_id');
    }

}
