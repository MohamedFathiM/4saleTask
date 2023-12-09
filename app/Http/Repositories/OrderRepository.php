<?php

namespace App\Http\Repositories;

use App\Http\Requests\API\V1\OrderRequest;
use App\Models\Meal;
use App\Models\Order;
use App\Models\Reservation;
use App\Support\Services\IPayeable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderRepository
{
    public function makeOrder(OrderRequest $request, Reservation $reservation): ?Order
    {
        try {

            DB::beginTransaction();
            $total = 0;
            foreach ($request->meals as $meal) {
                $mealModel = Meal::where('id', $meal['meal_id'])->first();

                if ($mealModel->where('available_quantity', '<', $meal['amount_to_pay'])->where('id', $meal['meal_id'])->exists()) {
                    throw ValidationException::withMessages([
                        'meals' => 'Meal with id ' . $meal['meal_id'] . ' is not available with quantity',
                    ]);
                }

                $total += (float) ($meal['amount_to_pay'] * ($mealModel->price - ($mealModel->price * $mealModel->discount) / 100));
            }


            $order =  Order::create([
                'table_id' => $reservation->table_id,
                'reservation_id' => $reservation->id,
                'customer_id' => $reservation->customer_id,
                'user_id' => auth()->id(),
                'total' => $total,
            ]);

            $order->orderDetail()->createMany($request->meals);
            DB::commit();

            return $order;
        } catch (\Exception $th) {
            DB::rollBack();

            throw $th;
        }
    }

    public function payOrder(Request $request, Order $order, IPayeable $paymentService): Order
    {
        $order->update([
            'is_paid' => true,
            'paid_at' => now(),
        ]);

        $paymentService->checkout($order);

        return $order->refresh();
    }

    public function isOrderExists(Reservation $reservation): bool
    {
        return Order::query()->where('reservation_id', $reservation->id)->exists();
    }

    public function isOrderPaid(Order $order): bool
    {
        return Order::query()->whereNotNull('paid_at')->where('id', $order->id)->exists();
    }
}
