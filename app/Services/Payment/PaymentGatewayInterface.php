<?php

namespace App\Services\Payment;

interface PaymentGatewayInterface
{
    public function processPayment(array $data): array;
    public function verifyPayment(string $transactionId): array;
    public function refund(string $transactionId, float $amount): array;
    public function handleWebhook(array $payload): array;
}