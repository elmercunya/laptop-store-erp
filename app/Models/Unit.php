<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unit extends Model
{
    use SoftDeletes;
    Use HasFactory;

    protected $fillable = [
        'product_id',
        'serial_number',
        'status',
    ];

    public function product(): BelongsTo {
        return $this->belongsTo(Product::class);
    }

    public function saleDetail(): HasOne {
        return $this->hasOne(SaleDetail::class);
    }
}
