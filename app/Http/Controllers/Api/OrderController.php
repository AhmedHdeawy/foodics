<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\PlaceOrderRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Services\OrderService\OrderServiceContract;

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
        $order = $this->orderService->placeOrder($request->validated());

        return response()->json($order, Response::HTTP_CREATED);
    }
}
