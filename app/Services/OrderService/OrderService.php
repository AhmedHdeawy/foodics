<?php
namespace App\Services\OrderService;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpKernel\Exception\GoneHttpException;

class OrderService implements OrderServiceContract
{

    protected Collection $dbProducts;
    protected array $requestPayload;

    public function __construct(
        protected OrderRepository $orderRepository,
        protected ProductRepository $productRepository
    ) {}

    public function placeOrder(array $data) : Model
    {
        $this->requestPayload = $data['products'];
        // Get the products details from DB
        $this->getProducts();

        // validate the quantity
        $this->validateQuantity();

        // persist the order in the database with the product items
        $this->persistTheOrder();

        // update the stock
        return $this->orderRepository->create([]);
    }

    private function getProducts() : void
    {
        $this->dbProducts = $this->productRepository
            ->getWhereIn(
                $this->getDataFromRequest($this->requestPayload, 'product_id'),
                ['ingredients', 'ingredients.stock']
            );
    }
    
    private function validateQuantity() : void
    {
        $productIdsWithQuantity = $this->getDataFromRequest($this->requestPayload, 'quantity', 'product_id');

        $this->dbProducts->map(function($product) use ($productIdsWithQuantity) {
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

    private function persistTheOrder() : void
    {
        $productIdsWithQuantity = $this->getDataFromRequest($this->requestPayload, 'quantity', 'product_id');

        $this->dbProducts->map(function($product) use ($productIdsWithQuantity) {
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