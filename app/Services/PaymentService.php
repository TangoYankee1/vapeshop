<?php

namespace App\Services;

use App\Models\Order;

class PaymentService
{
    public function processPayment(Order $order, string $paymentToken)
    {
        // In a real application, you would integrate with a payment gateway like Stripe or Braintree.
        // For this example, we'll just simulate a successful payment.
        return [
            'status' => 'success',
            'transaction_id' => uniqid(),
        ];
    }
}
