<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\PlaceOrderRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    /**
     * place a new order
     *
     * @param Request $request
     * @return JsonResponse
     **/
    public function __invoke(PlaceOrderRequest $request)
    {
        return response()->json([], 201);
    }
}
