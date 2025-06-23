<?php

namespace App\Services\Payment\Gateways;

use App\Services\Payment\PaymentGatewayInterface;
use Illuminate\Support\Facades\Http;
use Exception;

class NagadGateway implements PaymentGatewayInterface
{
    private $baseUrl;
    private $merchantId;
    private $publicKey;
    private $privateKey;

    public function __construct()
    {
        $this->baseUrl = config('services.nagad.base_url');
        $this->merchantId = config('services.nagad.merchant_id');
        $this->publicKey = config('services.nagad.public_key');
        $this->privateKey = config('services.nagad.private_key');
    }

    public function processPayment(array $data): array
    {
        try {
            $orderId = $data['transaction_id'];
            $dateTime = date('YmdHis');
            
            // Initialize payment
            $initializeData = [
                'merchantId' => $this->merchantId,
                'orderId' => $orderId,
                'datetime' => $dateTime,
                'challenge' => $this->generateChallenge(),
            ];

            $response = Http::post($this->baseUrl . '/api/dfs/check-out/initialize/' . $this->merchantId . '/' . $orderId, $initializeData);

            if (!$response->successful()) {
                throw new Exception('Nagad initialization failed');
            }

            $initResult = $response->json();

            // Create payment
            $sensitiveData = [
                'merchantId' => $this->merchantId,
                'orderId' => $orderId,
                'amount' => $data['amount'],
                'currencyCode' => '050', // BDT
                'challenge' => $initResult['challenge'],
            ];

            $signature = $this->generateSignature($sensitiveData);

            $paymentData = [
                'sensitiveData' => $this->encryptData($sensitiveData),
                'signature' => $signature,
                'merchantCallbackURL' => $data['success_url'],
            ];

            $paymentResponse = Http::post($this->baseUrl . '/api/dfs/check-out/complete/' . $this->merchantId, $paymentData);

            if ($paymentResponse->successful()) {
                $result = $paymentResponse->json();
                return [
                    'status' => 'pending',
                    'payment_ref' => $result['paymentReferenceId'],
                    'redirect_url' => $result['callBackUrl'],
                ];
            }

            throw new Exception('Nagad payment creation failed');

        } catch (\Exception $e) {
            throw new Exception('Nagad payment failed: ' . $e->getMessage());
        }
    }

    public function verifyPayment(string $paymentRef): array
    {
        try {
            $response = Http::get($this->baseUrl . '/api/dfs/verify/payment/' . $paymentRef);

            if ($response->successful()) {
                $result = $response->json();
                
                if ($result['status'] === 'Success') {
                    return [
                        'status' => 'completed',
                        'transaction_id' => $result['orderId'],
                        'amount' => floatval($result['amount']),
                        'currency' => 'BDT',
                        'nagad_ref' => $result['paymentRefId'],
                    ];
                }
            }

            return [
                'status' => 'failed',
                'message' => 'Payment verification failed',
            ];

        } catch (\Exception $e) {
            throw new Exception('Payment verification failed: ' . $e->getMessage());
        }
    }

    public function refund(string $paymentRef, float $amount): array
    {
        // Nagad refund implementation
        throw new Exception('Nagad refund not implemented');
    }

    public function handleWebhook(array $payload): array
    {
        // Nagad uses callback URLs instead of webhooks
        return ['status' => 'ignored'];
    }

    private function generateChallenge(): string
    {
        return bin2hex(random_bytes(16));
    }

    private function generateSignature(array $data): string
    {
        $dataString = json_encode($data);
        openssl_sign($dataString, $signature, $this->privateKey, OPENSSL_ALGO_SHA256);
        return base64_encode($signature);
    }

    private function encryptData(array $data): string
    {
        $publicKey = openssl_pkey_get_public($this->publicKey);
        openssl_public_encrypt(json_encode($data), $encrypted, $publicKey);
        return base64_encode($encrypted);
    }
}