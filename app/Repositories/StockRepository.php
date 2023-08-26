<?php
namespace App\Repositories;
use App\Models\Stock;

class StockRepository extends BaseRepository
{

    public function model() : string
    {
        return Stock::class;
    }
}