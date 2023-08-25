<?php
namespace App\Services\StockService;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpKernel\Exception\GoneHttpException;

class StockService implements StockServiceContract
{

    protected Collection $dbProducts;
    
    protected Order $order;
    
    public function __construct(
        protected OrderRepository $orderRepository,
        protected ProductRepository $productRepository
    ) {}

    public function updateStock(int $orderId) : void
    {
        DB::beginTransaction();
        try {
            
            DB::commit();

        } catch (\Throwable $th) {
            DB::rollBack();
            throw new GoneHttpException($th->getMessage());
        }
    }
}