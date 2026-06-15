<?php
session_start();
include('includes/config.php');

$order_id = 123; // Replace with actual order ID from cart or session

// Create payment request
$paymentData = [
    'intent' => 'sale',
    'payer' => ['payment_method' => 'paypal'],
    'redirect_urls' => [
        'return_url' => 'http://localhost/shopping/execute-payment.php',
        'cancel_url' => 'http://localhost/shopping/payment-cancel.php',
    ],
    'transactions' => [[
        'amount' => [
            'total' => '10.00', // Replace with actual amount
            'currency' => 'USD'
        ],
        'description' => "Order #{$order_id}"
    ]]
];

// Get Access Token
$ch = curl_init(PAYPAL_API_BASE . "/v1/oauth2/token");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_USERPWD => PAYPAL_CLIENT_ID . ':' . PAYPAL_SECRET,
    CURLOPT_POSTFIELDS => "grant_type=client_credentials",
    CURLOPT_POST => true
]);
$result = json_decode(curl_exec($ch));
$accessToken = $result->access_token;
curl_close($ch);

// Create the payment
$ch = curl_init(PAYPAL_API_BASE . "/v1/payments/payment");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Authorization: Bearer $accessToken"
    ],
    CURLOPT_POSTFIELDS => json_encode($paymentData)
]);

$response = json_decode(curl_exec($ch), true);
curl_close($ch);

// Redirect to PayPal approval URL
if (isset($response['links'])) {
    foreach ($response['links'] as $link) {
        if ($link['rel'] === 'approval_url') {
            header("Location: " . $link['href']);
            exit;
        }
    }
}

echo "Unable to create PayPal payment.";
