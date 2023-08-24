<?php
namespace App\Services\OrderService;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class OrderService implements OrderServiceContract
{

    public function __construct(
        protected OrderRepository $orderRepository,
        protected ProductRepository $productRepository
    ) {}

    public function placeOrder(array $data) : Model
    {
        // Get the products details from DB
        $dbProducts = $this->getProducts($data['products']);

        // validate the quantity

        // persist the order in the database with the product items

        // update the stock
    }

    private function getProducts(array $productsRequest) : Collection
    {
        $productsIds = collect($productsRequest)->pluck('product_id')->toArray();

        return $this->productRepository->getWhereIn($productsIds);
    }
}