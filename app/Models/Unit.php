<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'serial_number',
        'status',
    ];

    public function product(): BelongsTo {
        return $this->belongsTo(Product::class);
    }

    public function saleDetail(): HashOne {
        return $this->hashOne(SalDetail::class);
    }
}
