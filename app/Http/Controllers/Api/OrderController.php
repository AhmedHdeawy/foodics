<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\PlaceOrderRequest;
use App\Services\OrderService\OrderServiceContract;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function __construct(private OrderServiceContract $orderService)
    {
    }
    /**
     * place a new order
     *
     * @param Request $request
     * @return JsonResponse
     **/
    public function __invoke(PlaceOrderRequest $request)
    {
        $order = $this->orderService->placeOrder($request->all());

        return response()->json($order, 201);
    }
}
