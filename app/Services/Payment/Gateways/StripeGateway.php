<?php

namespace App\Services\Payment\Gateways;

use App\Services\Payment\PaymentGatewayInterface;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;
use Exception;

class StripeGateway implements PaymentGatewayInterface
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function processPayment(array $data): array
    {
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $data['amount'] * 100, // Convert to cents
                'currency' => strtolower($data['currency']),
                'description' => $data['description'] ?? null,
                'metadata' => [
                    'transaction_id' => $data['transaction_id'],
                    'customer_email' => $data['customer_email'],
                ],
                'receipt_email' => $data['customer_email'],
            ]);

            return [
                'status' => 'pending',
                'payment_intent_id' => $paymentIntent->id,
                'client_secret' => $paymentIntent->client_secret,
                'redirect_url' => null, // Stripe uses client-side confirmation
            ];

        } catch (ApiErrorException $e) {
            throw new Exception('Stripe payment failed: ' . $e->getMessage());
        }
    }

    public function verifyPayment(string $paymentIntentId): array
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

            return [
                'status' => $paymentIntent->status === 'succeeded' ? 'completed' : 'failed',
                'transaction_id' => $paymentIntent->metadata->transaction_id,
                'amount' => $paymentIntent->amount / 100,
                'currency' => strtoupper($paymentIntent->currency),
                'payment_method' => $paymentIntent->payment_method,
            ];

        } catch (ApiErrorException $e) {
            throw new Exception('Payment verification failed: ' . $e->getMessage());
        }
    }

    public function refund(string $paymentIntentId, float $amount): array
    {
        try {
            $refund = \Stripe\Refund::create([
                'payment_intent' => $paymentIntentId,
                'amount' => $amount * 100,
            ]);

            return [
                'status' => 'success',
                'refund_id' => $refund->id,
                'amount' => $refund->amount / 100,
            ];

        } catch (ApiErrorException $e) {
            throw new Exception('Refund failed: ' . $e->getMessage());
        }
    }

    public function handleWebhook(array $payload): array
    {
        $endpointSecret = config('services.stripe.webhook_secret');
        
        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload['body'],
                $payload['signature'],
                $endpointSecret
            );

            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $paymentIntent = $event->data->object;
                    return [
                        'status' => 'success',
                        'transaction_id' => $paymentIntent->metadata->transaction_id,
                        'data' => [
                            'payment_intent_id' => $paymentIntent->id,
                            'amount' => $paymentIntent->amount / 100,
                        ],
                    ];

                case 'payment_intent.payment_failed':
                    return [
                        'status' => 'failed',
                        'transaction_id' => $event->data->object->metadata->transaction_id,
                    ];

                default:
                    return ['status' => 'ignored'];
            }

        } catch (\Exception $e) {
            throw new Exception('Webhook verification failed: ' . $e->getMessage());
        }
    }
}