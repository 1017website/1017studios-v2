<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['name', 'logo', 'order', 'is_active'];

    protected $casts = [
        'order' => 'integer',
        'is_active' => 'boolean',
    ];
}
