<?php

namespace App\Services\Payment\Gateways;

use App\Services\Payment\PaymentGatewayInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

class BkashGateway implements PaymentGatewayInterface
{
    private $baseUrl;
    private $username;
    private $password;
    private $appKey;
    private $appSecret;

    public function __construct()
    {
        $this->baseUrl = config('services.bkash.base_url');
        $this->username = config('services.bkash.username');
        $this->password = config('services.bkash.password');
        $this->appKey = config('services.bkash.app_key');
        $this->appSecret = config('services.bkash.app_secret');
    }

    public function processPayment(array $data): array
    {
        try {
            // Get token with caching
            $token = $this->getToken();

            // Create payment
            $response = Http::withHeaders([
                'Authorization' => $token,
                'X-APP-Key' => $this->appKey,
            ])->post($this->baseUrl . '/checkout/create', [
                'mode' => '0011',
                'payerReference' => ' ',
                'callbackURL' => $data['success_url'],
                'amount' => (string) $data['amount'],
                'currency' => 'BDT',
                'intent' => 'sale',
                'merchantInvoiceNumber' => 'inv' . time(),
            ]);

            $result = $response->json();

            if ($response->successful() && isset($result['bkashURL'])) {
                return [
                    'status' => 'pending',
                    'payment_id' => $result['paymentID'],
                    'redirect_url' => $result['bkashURL'],
                ];
            }

            // Handle token expiry
            if ($response->status() === 401) {
                Log::warning("BkashGateway: Token expired, refreshing...");
                Cache::forget('bkash_id_token');
                return $this->processPayment($data); // Retry with new token
            }

            throw new Exception('bKash payment creation failed');

        } catch (\Exception $e) {
            throw new Exception('bKash payment failed: ' . $e->getMessage());
        }
    }

    public function verifyPayment(string $paymentId): array
    {
        try {
            $token = $this->getToken();

            $response = Http::withHeaders([
                'Authorization' => $token,
                'X-APP-Key' => $this->appKey,
            ])->post($this->baseUrl . '/checkout/execute', [
                'paymentID' => $paymentId,
            ]);

            $result = $response->json();

            if ($response->successful() && $result['transactionStatus'] === 'Completed' && $result['statusCode'] === '0000') {
                return [
                    'status' => 'completed',
                    'transaction_id' => $result['trxID'] ?? $result['paymentID'],
                    'amount' => floatval($result['amount']),
                    'currency' => $result['currency'],
                    'bkash_trx_id' => $result['trxID'],
                ];
            }

            // Handle token expiry
            if ($response->status() === 401) {
                Cache::forget('bkash_id_token');
                return $this->verifyPayment($paymentId); // Retry
            }

            return [
                'status' => 'failed',
                'message' => $result['errorMessage'] ?? 'Payment failed',
            ];

        } catch (\Exception $e) {
            throw new Exception('Payment verification failed: ' . $e->getMessage());
        }
    }

    public function refund(string $paymentId, float $amount): array
    {
        try {
            $token = $this->getToken();

            $response = Http::withHeaders([
                'Authorization' => $token,
                'X-APP-Key' => $this->appKey,
            ])->post($this->baseUrl . '/checkout/payment/refund', [
                'paymentID' => $paymentId,
                'amount' => (string) $amount,
                'trxID' => $paymentId,
                'sku' => 'refund',
                'reason' => 'Customer refund request',
            ]);

            $result = $response->json();

            if ($response->successful() && $result['transactionStatus'] === 'Completed') {
                return [
                    'status' => 'success',
                    'refund_id' => $result['refundTrxID'],
                    'amount' => floatval($result['amount']),
                ];
            }

            throw new Exception($result['errorMessage'] ?? 'Refund failed');

        } catch (\Exception $e) {
            throw new Exception('Refund failed: ' . $e->getMessage());
        }
    }

    public function handleWebhook(array $payload): array
    {
        // bKash doesn't use webhooks in the same way
        // Payment status is checked via execute API
        return ['status' => 'ignored'];
    }

    /**
     * Get token with caching
     */
    private function getToken(): string
    {
        $cacheKey = 'bkash_id_token';
        $idToken = Cache::get($cacheKey);
        
        if (!$idToken) {
            $idToken = $this->grantToken()['id_token'];
            Cache::put($cacheKey, $idToken, now()->addMinutes(55)); // cache for 55 minutes
            Log::info("bkashAuthService---> new token generated and cached");
        } else {
            Log::info("bkashAuthService---Token from existing cache");
        }
        
        return $idToken;
    }

    /**
     * Grant new token from bKash API
     */
    private function grantToken(): array
    {
        $response = Http::withHeaders([
            'username' => $this->username,
            'password' => $this->password,
        ])->post($this->baseUrl . '/checkout/token/grant', [
            'app_key' => $this->appKey,
            'app_secret' => $this->appSecret,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new Exception('Failed to get bKash token');
    }
}