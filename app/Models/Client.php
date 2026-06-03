<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Client extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'document_type',
        'document_number',
        'name',
    ];

    public function sales():HasMany {
        return $this->hasMany(Sale::class);
    }
}
