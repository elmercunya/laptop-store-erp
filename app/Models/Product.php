<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;




class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'category_id',
        'sale_price',
        'image'
    ];

    public function category(): BelongsTo {
        return $this->belongsTo(Category::class);
    }

    public function units():HasMany {
        return $this->hasMany(Unit::class);
    }
}
