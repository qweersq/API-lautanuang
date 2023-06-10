<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
