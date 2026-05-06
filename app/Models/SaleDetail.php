<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleDetail extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'sale_id',
        'unit_id',
        'price',
    ];

    public function sale():BelongsTo {
        return $this->belongsTo(Sale::class);
    }

    public function unit():BelongsTo {
        return $this->belongsTo(Unit::class);
    }
}
