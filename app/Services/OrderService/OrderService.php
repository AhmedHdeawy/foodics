<?php
namespace App\Services\OrderService;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpKernel\Exception\GoneHttpException;

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
    
    private function validateQuantity(Collection $products, array $productsRequest) : void
    {
        $productIdsWithQuantity = $this->getDataFromRequest($productsRequest, 'quantity', 'product_id');

        $products->map(function($product) use ($productIdsWithQuantity) {
            foreach ($product->ingredients as $ingred) {
                $currentQuantityInStock = $ingred->stock['current_stock'];
                $productQuantityUsed = $ingred->pivot['quantity'];
                $quantityRequested = $productIdsWithQuantity[$product->id];
                if ($currentQuantityInStock < ($quantityRequested * $productQuantityUsed)) {
                    throw new GoneHttpException("the product with id {$product->id} out of stock");
                }
            }
        });
    }

    private function getDataFromRequest(array $productsRequest, string $value, string $key = null) : array
    {
        return collect($productsRequest)->pluck($value, $key)->toArray();
    }
}