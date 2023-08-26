<?php
namespace App\Services\OrderService;
use Illuminate\Database\Eloquent\Model;

interface OrderServiceContract
{
    public function placeOrder(array $data) : Model;
}