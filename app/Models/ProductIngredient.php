<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductIngredient extends Pivot
{
    protected $fillable = ['product_id', 'ingredient_id', 'quantity'];

}
