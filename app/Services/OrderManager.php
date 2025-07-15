<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class OrderManager
{
    public function createOrder(array $data): JsonResponse
    {
        return DB::transaction(function () use ($data) {
            $user = User::find($data['user_id']);
            $orderItems = [];

            foreach ($data['items'] as $item) {
                $product = Product::where('id', $item['product_id'])->lockForUpdate()->first();

                if ($product->stock <= 0) {
                    return response()->json([
                        'message' => "Товар '{$product->name}' отсутствует на складе."
                    ], 422);
                }

                if ($product->stock < $item['amount']) {
                    return response()->json([
                        'message' => "Товар '{$product->name}' доступен для заказа в количестве {$product->stock} шт."
                    ], 422);
                }

                $orderItems[] = [
                    'product_id' => $product->id,
                    'amount' => $item['amount'],
                    'price' => $product->price,
                ];

                $product->decrement('stock', $item['amount']);
            }

            $order = Order::create([
                'user_id' => $user->id,
                'number' => (string) random_int(100000, 999999),
                'status' => Order::STATUS_DRAFT,
                'created_at' => now(),
            ]);

            $order->items()->createMany($orderItems);

            return response()->json([
                'message' => "Заказ '{$order->number}' успешно создан и товары зарезервированы.",
                'order_id' => $order->id
            ]);
        });
    }

    public function approveOrder(array $data): JsonResponse
    {
        return DB::transaction(function () use ($data) {
            $order = Order::with('items')->lockForUpdate()->find($data['order_id']);
            $user = User::lockForUpdate()->find($data['user_id']);

            if (!$order || $order->user_id !== $user->id) {
                return response()->json(['message' => 'Заказ не найден или не принадлежит пользователю.'], 404);
            }

            if ($order->status !== Order::STATUS_DRAFT) {
                return response()->json(['message' => 'Заказ уже обработан.'], 422);
            }

            $total = $order->items->sum(function ($item) {
                return $item->price * $item->amount;
            });

            if ($user->balance < $total) {
                return response()->json([
                    'message' => 'Недостаточно средств на балансе.',
                    'required' => $total,
                    'balance' => $user->balance
                ], 422);
            }

            $user->decrement('balance', $total);

            $order->update([
                'status' => Order::STATUS_APPROVED,
            ]);

            return response()->json([
                'message' => 'Заказ успешно оплачен.',
                'order_id' => $order->id
            ]);
        });
    }

}
