<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ingredient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name'];

    public function stock() : HasOne
    {
        return $this->hasOne(Stock::class, 'ingredient_id');
    }

    public function product() : BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_ingredients')->withPivot('quantity');
    }
}
