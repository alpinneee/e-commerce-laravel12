<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use Midtrans\Notification;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
        
        // Disable SSL verification for development
        if (!config('midtrans.is_production')) {
            Config::$curlOptions = [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ];
        }
        
        // Suppress PHP warnings from Midtrans library
        error_reporting(E_ALL & ~E_WARNING);
        
        // Disable SSL verification for development
        if (!config('midtrans.is_production')) {
            Config::$curlOptions = [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ];
        }
    }

    /**
     * Create Snap token for payment
     */
    public function createSnapToken(Order $order)
    {
        $params = $this->buildTransactionParams($order);
        $snapToken = Snap::getSnapToken($params);
        return $snapToken;
    }

    /**
     * Build transaction parameters for Midtrans
     */
    private function buildTransactionParams(Order $order)
    {
        // Generate unique order ID to avoid duplicates
        $uniqueOrderId = $order->order_number . '-' . $order->created_at->timestamp;
        
        return [
            'transaction_details' => [
                'order_id' => $uniqueOrderId,
                'gross_amount' => (int) $order->total_amount,
            ],
            'customer_details' => [
                'first_name' => 'Customer',
                'email' => 'customer@example.com',
                'phone' => '08123456789',
            ]
        ];
    }

    /**
     * Handle notification from Midtrans
     */
    public function handleNotification()
    {
        try {
            $notification = new Notification();
            
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status;
            $orderId = $notification->order_id;
            
            // Find order by order number
            $order = Order::where('order_number', $orderId)->first();
            
            if (!$order) {
                Log::error('Order not found for Midtrans notification: ' . $orderId);
                return false;
            }

            Log::info('Midtrans Notification: ' . $orderId . ' - Status: ' . $transactionStatus);

            // Update order based on transaction status
            switch ($transactionStatus) {
                case 'capture':
                    if ($fraudStatus == 'challenge') {
                        $this->updateOrderPaymentStatus($order, 'pending', 'Payment challenged, please take action');
                    } else if ($fraudStatus == 'accept') {
                        $this->updateOrderPaymentStatus($order, 'paid', 'Payment successful');
                    }
                    break;
                    
                case 'settlement':
                    $this->updateOrderPaymentStatus($order, 'paid', 'Payment settled');
                    break;
                    
                case 'pending':
                    $this->updateOrderPaymentStatus($order, 'pending', 'Payment pending');
                    break;
                    
                case 'deny':
                    $this->updateOrderPaymentStatus($order, 'failed', 'Payment denied');
                    break;
                    
                case 'cancel':
                case 'expire':
                    $this->updateOrderPaymentStatus($order, 'failed', 'Payment cancelled/expired');
                    break;
                    
                case 'refund':
                case 'partial_refund':
                    $this->updateOrderPaymentStatus($order, 'refunded', 'Payment refunded');
                    break;
                    
                default:
                    Log::warning('Unknown Midtrans transaction status: ' . $transactionStatus);
                    break;
            }
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update order payment status
     */
    private function updateOrderPaymentStatus(Order $order, string $paymentStatus, string $note)
    {
        // Get transaction details from Midtrans
        $transactionDetails = null;
        try {
            $notification = new Notification();
            $transactionDetails = [
                'transaction_id' => $notification->transaction_id ?? null,
                'order_id' => $notification->order_id ?? null,
                'payment_type' => $notification->payment_type ?? null,
                'transaction_time' => $notification->transaction_time ?? null,
                'transaction_status' => $notification->transaction_status ?? null,
                'fraud_status' => $notification->fraud_status ?? null,
                'gross_amount' => $notification->gross_amount ?? null,
                'currency' => $notification->currency ?? 'IDR',
            ];
        } catch (\Exception $e) {
            Log::warning('Could not get transaction details: ' . $e->getMessage());
        }

        $order->update([
            'payment_status' => $paymentStatus,
            'payment_details' => $transactionDetails,
            'notes' => $order->notes . "\n\nMidtrans: " . $note . ' at ' . now()->format('Y-m-d H:i:s')
        ]);

        // Auto update order status for successful payments
        if ($paymentStatus === 'paid' && $order->status === 'pending') {
            $order->update(['status' => 'processing']);
        }
        
        Log::info("Order {$order->order_number} payment status updated to: {$paymentStatus}");
    }

    /**
     * Get transaction status from Midtrans
     */
    public function getTransactionStatus(string $orderId)
    {
        try {
            $status = Transaction::status($orderId);
            return $status;
        } catch (\Exception $e) {
            Log::error('Failed to get Midtrans transaction status: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get transaction details for order
     */
    public function getTransactionDetails(Order $order)
    {
        // First try to get from stored payment_details
        if ($order->payment_details) {
            return $order->payment_details;
        }

        // If not stored, try to fetch from Midtrans
        $uniqueOrderId = $order->order_number . '-' . $order->created_at->timestamp;
        return $this->getTransactionStatus($uniqueOrderId);
    }

    /**
     * Cancel transaction
     */
    public function cancelTransaction(string $orderId)
    {
        try {
            $cancel = Transaction::cancel($orderId);
            return $cancel;
        } catch (\Exception $e) {
            Log::error('Failed to cancel Midtrans transaction: ' . $e->getMessage());
            return null;
        }
    }
} 