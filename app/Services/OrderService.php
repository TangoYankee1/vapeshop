<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected $inventoryService;
    protected $paymentService;

    public function __construct(InventoryService $inventoryService, PaymentService $paymentService)
    {
        $this->inventoryService = $inventoryService;
        $this->paymentService = $paymentService;
    }

    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            // 1. Validate cart and check stock
            $this->validateCart($data['items']);

            // 2. Calculate totals
            $totals = $this->calculateTotals($data['items']);

            // 3. Create order
            $order = Order::create([
                'user_id' => $data['user_id'],
                'status' => 'pending',
                'total' => $totals['total'],
                'subtotal' => $totals['subtotal'],
                'discount' => $totals['discount'],
                'shipping' => $totals['shipping'],
            ]);

            // 4. Add order items
            foreach ($data['items'] as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => Product::find($item['product_id'])->price,
                ]);
            }

            // 5. Process payment
            $payment = $this->paymentService->processPayment($order, $data['payment_token']);

            // 6. Update inventory
            $this->inventoryService->updateInventory($order);

            // 7. Return order
            return $order;
        });
    }

    private function validateCart(array $items)
    {
        foreach ($items as $item) {
            $product = Product::find($item['product_id']);
            if (!$product || $product->stock < $item['quantity']) {
                throw new \Exception('Invalid cart item or insufficient stock.');
            }
        }
    }

    private function calculateTotals(array $items): array
    {
        $subtotal = 0;
        foreach ($items as $item) {
            $product = Product::find($item['product_id']);
            $subtotal += $product->price * $item['quantity'];
        }

        // For now, discount and shipping are hardcoded
        $discount = 0;
        $shipping = 10;

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'shipping' => $shipping,
            'total' => $subtotal - $discount + $shipping,
        ];
    }
}
