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
        $this->validateQuantity($dbProducts, $data['products']);

        // persist the order in the database with the product items

        // update the stock
    }

    private function getProducts(array $productsRequest) : Collection
    {
        return $this->productRepository
            ->getWhereIn(
                $this->getDataFromRequest($productsRequest, 'product_id'),
                ['ingredients', 'ingredients.stock']
            );
    }
    
    private function validateQuantity(Collection $products, array $productsRequest) : Collection
    {
        $productsIds = $this->getDataFromRequest($productsRequest, 'quantity');

        return $this->productRepository->getWhereIn($productsIds);
    }

    private function getDataFromRequest(array $productsRequest, string $key) : array
    {
        return collect($productsRequest)->pluck($key)->toArray();       
    }
}