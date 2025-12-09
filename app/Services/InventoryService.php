<?php

namespace App\Services;

use App\Models\Order;

class InventoryService
{
    public function updateInventory(Order $order)
    {
        foreach ($order->items as $item) {
            $item->product->decrement('stock', $item->quantity);
        }
    }
}
