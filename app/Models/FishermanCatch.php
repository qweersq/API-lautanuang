<?php

namespace App\Models;

use App\Models\FishermanTim;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FishermanCatch extends Model
{
    use HasFactory;

    protected $table = 'fisherman_catch';

    protected $fillable = [
        'fisherman_tim_id',
        'weight'
    ];
    public function fishermanCatchDetails()
    {
        return $this->hasMany(FishermanCatchDetail::class, 'fishing_catch_id', 'id');
    }

    public function fishermanTim()
    {
        return $this->belongsTo(FishermanTim::class);
    }
}
