<?php

namespace App\Services;

use Safaricom\Mpesa\Mpesa;
use Illuminate\Support\Facades\Log;
use Exception;

class MpesaService
{
    protected $mpesa;

    public function __construct()
    {
        $this->mpesa = new Mpesa();
    }

    public function initiatePayment($amount, $phone, $description)
    {
        try {
            // Validation
            if (!is_numeric($amount) || $amount <= 0) {
                throw new Exception('Invalid amount provided.');
            }

            if (!preg_match('/^2547\d{8}$/', $phone)) {
                throw new Exception('Invalid phone number format. It should be in the format 2547XXXXXXXX.');
            }

            // Fetching config values
            $BusinessShortCode = config('mpesa.shortcode');
            $PassKey = config('mpesa.passkey');
            $TransactionType = "CustomerPayBillOnline";
            $CallBackURL = route('mpesa.callback');  // Assuming you have a route for this
            $AccountReference = "School Fees";
            $Remarks = "Payment for School Fees";

            // Initiating the STK Push Simulation
            $response = $this->mpesa->STKPushSimulation(
                $BusinessShortCode,
                $PassKey,
                $TransactionType,
                $amount,
                $phone,
                $BusinessShortCode,
                $phone,
                $CallBackURL,
                $AccountReference,
                $description,
                $Remarks
            );

            // Check response
            if (isset($response['errorCode'])) {
                throw new Exception('Mpesa API Error: ' . $response['errorMessage']);
            }

            return $response;

        } catch (Exception $e) {
            Log::error('Mpesa Payment Error: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'An error occurred while processing the payment. Please try again later.',
                'developerMessage' => $e->getMessage()
            ];
        }
    }

    public function handleCallback($data)
    {
        try {
            if (!isset($data['Body']['stkCallback'])) {
                throw new Exception('Invalid callback data received.');
            }

            $callbackData = $data['Body']['stkCallback'];

            return [
                'status' => 'success',
                'message' => 'Payment processed successfully.',
                'data' => $callbackData
            ];

        } catch (Exception $e) {
            Log::error('Mpesa Callback Error: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'An error occurred while processing the payment callback.',
                'developerMessage' => $e->getMessage()
            ];
        }
    }
}

