<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stock extends Model
{
    use HasFactory;

    public function ingredients() : HasMany
    {
        return $this->hasMany(Ingredient::class, 'ingredient_id');
    }
}
