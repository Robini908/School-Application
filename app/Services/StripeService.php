<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\PaymentIntent;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('stripe.secret_key'));
    }

    public function createPaymentIntent($amount, $currency = 'usd', $metadata = [])
    {
        return PaymentIntent::create([
            'amount' => $amount * 100, // Convert to cents
            'currency' => $currency,
            'metadata' => $metadata,
        ]);
    }

    public function handleWebhook($payload)
    {
        // Handle Stripe webhook events
        return $payload;
    }
}
