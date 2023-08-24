<?php
namespace App\Services\OrderService;
use Illuminate\Database\Eloquent\Model;

class OrderService implements OrderServiceContract
{
    public function placeOrder(array $data) : Model
    {
        dd(7788);
    }
}