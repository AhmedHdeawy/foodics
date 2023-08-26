<?php
namespace App\Services\StockService;

interface StockServiceContract
{
    public function updateStock(int $orderId) : void;
}