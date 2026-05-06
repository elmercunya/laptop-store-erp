<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'client_id',
        'voucher',
        'number',
        'date',
        'subtotal',
        'igv',
        'total',
        'status'
    ];

    public function client():BelongsTo {
        return $this->belongsTo(Client::class);
    }

    public function saleDetails():HasMany {
        return $this->hasMany(SaleDetail::class);
    }
}
