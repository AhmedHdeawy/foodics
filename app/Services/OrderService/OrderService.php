<?php
namespace App\Services\OrderService;
use App\Repositories\OrderRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class OrderService implements OrderServiceContract
{

    public function __construct(protected OrderRepository $orderRepository) {}

    public function placeOrder(array $data) : Model
    {
        // Get the products details from DB

        $this->getProducts($data['products']);

        // validate the quantity

        // persist the order in the database with the product items

        // update the stock
    }

    private function getProducts() : Collection
    {

    }
}