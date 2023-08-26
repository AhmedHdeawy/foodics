<?php
namespace App\Repositories;
use App\Models\Ingredient;

class IngredientRepository extends BaseRepository
{

    public function model() : string
    {
        return Ingredient::class;
    }
}