<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $fillable = ['name', 'hex_code', 'active'];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
