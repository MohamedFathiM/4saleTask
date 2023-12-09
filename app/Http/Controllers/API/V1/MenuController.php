<?php

namespace App\Http\Controllers\API\V1;

use App\Enums\PaymentType;
use App\Http\Controllers\Controller;
use App\Http\Repositories\MenuRepository;
use App\Http\Repositories\OrderRepository;
use App\Http\Requests\API\V1\OrderRequest;
use App\Http\Resources\API\V1\MealResource;
use App\Http\Resources\API\V1\OrderResource;
use App\Models\Order;
use App\Models\Reservation;
use App\Support\Services\IPayeable;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MenuController extends Controller
{
    private MenuRepository $menuRepository;
    private OrderRepository $orderRepository;

    public function __construct(
        MenuRepository $menuRepository,
        OrderRepository $orderRepository
    ) {
        $this->menuRepository = $menuRepository;
        $this->orderRepository = $orderRepository;
    }

    public function index(Request $request)
    {
        $items = $this->menuRepository->index($request);

        return $this->paginateResponse(
            data: MealResource::collection($items),
            collection: $items,
        );
    }

    public function makeOrder(OrderRequest $request, Reservation $reservation)
    {
        if ($this->orderRepository->isOrderExists($reservation)) {
            throw ValidationException::withMessages([
                'order' => 'Order is Created Before with Id ' . Order::query()->where('reservation_id', $reservation->id)->first()?->id,
            ]);
        }

        $order = $this->orderRepository->makeOrder($request, $reservation);

        return $this->apiResource(data: OrderResource::make($order));
    }

    public function payOrder(Request $request, Order $order, IPayeable $service)
    {
        if ($this->orderRepository->isOrderPaid($order)) {
            throw ValidationException::withMessages([
                'order' => 'Order is Already Paid with Id ' . $order->id,
            ]);
        }

        $this->orderRepository->payOrder($request, $order, $service);

        return $this->apiResource(data: OrderResource::make($order), message: 'Order is paid successfully');
    }
}
