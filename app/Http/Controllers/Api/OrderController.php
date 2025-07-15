<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApproveOrderRequest;
use App\Http\Requests\CreateOrderRequest;
use App\Services\OrderManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected OrderManager $manager;

    public function __construct(OrderManager $manager)
    {
        $this->manager = $manager;
    }

    public function createOrder(CreateOrderRequest $request): JsonResponse
    {
        return $this->manager->createOrder($request->validated());
    }

    public function approveOrder(ApproveOrderRequest $request): JsonResponse
    {
        return $this->manager->approveOrder($request->validated());
    }
}
