<?php

namespace App\Services\Payment;

use App\Services\Payment\Gateways\StripeGateway;
use App\Services\Payment\Gateways\BkashGateway;
use App\Services\Payment\Gateways\NagadGateway;
use Exception;

class PaymentGatewayFactory
{
    /**
     * Create payment gateway instance
     */
    public function make(string $gateway): PaymentGatewayInterface
    {
        return match($gateway) {
            'stripe' => new StripeGateway(),
            'bkash' => new BkashGateway(),
            'nagad' => new NagadGateway(),
            default => throw new Exception("Payment gateway [{$gateway}] not supported.")
        };
    }

    /**
     * Get available payment gateways
     */
    public function available(): array
    {
        return [
            'stripe' => [
                'name' => 'Credit/Debit Card',
                'icon' => 'fab fa-cc-stripe',
                'currencies' => ['USD', 'BDT', 'EUR', 'GBP'],
                'countries' => 'all',
            ],
            'bkash' => [
                'name' => 'bKash',
                'icon' => 'bkash',
                'currencies' => ['BDT'],
                'countries' => ['BD'],
            ],
            'nagad' => [
                'name' => 'Nagad',
                'icon' => 'nagad',
                'currencies' => ['BDT'],
                'countries' => ['BD'],
            ],
        ];
    }
}