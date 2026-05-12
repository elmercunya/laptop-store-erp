<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'user',
        'password',
        'role',
    ];

    public function isAdmin(): bool {
        return $this->role === 'admin';
    }

    public function canSell() {
        return in_array($this->role, ['admin', 'empleado']);
    }
}
