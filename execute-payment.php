<?php
session_start();
include('includes/config.php');

if (!isset($_GET['paymentId']) || !isset($_GET['PayerID'])) {
    die('Payment failed or canceled.');
}

$paymentId = $_GET['paymentId'];
$payerId = $_GET['PayerID'];

// Step 1: Get Access Token
$ch = curl_init(PAYPAL_API_BASE . "/v1/oauth2/token");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_USERPWD => PAYPAL_CLIENT_ID . ":" . PAYPAL_SECRET,
    CURLOPT_POSTFIELDS => "grant_type=client_credentials",
    CURLOPT_POST => true
]);
$response = json_decode(curl_exec($ch));
$accessToken = $response->access_token ?? '';
curl_close($ch);

if (!$accessToken) {
    die("Unable to get access token from PayPal.");
}

// Step 2: Execute the payment
$ch = curl_init(PAYPAL_API_BASE . "/v1/payments/payment/$paymentId/execute");
curl_setopt_array($ch, [
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Authorization: Bearer $accessToken"
    ],
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS => json_encode(['payer_id' => $payerId])
]);
$response = json_decode(curl_exec($ch), true);
curl_close($ch);

// Step 3: Check payment approval
if (isset($response['state']) && $response['state'] === 'approved') {
    // Extract order ID from description
    $description = $response['transactions'][0]['description'] ?? '';
    preg_match('/Order #(\d+)/', $description, $matches);
    $order_id = $matches[1] ?? '';

    if ($order_id) {
        $stmt = $con->prepare("UPDATE payments SET status='approved', paypal_payment_id=? WHERE order_id=?");
        $stmt->bind_param("ss", $paymentId, $order_id);
        $stmt->execute();

        echo "<h2>Payment successful for Order #$order_id!</h2>";
    } else {
        echo "Payment approved, but order ID not found in description.";
    }
} else {
    echo "<h2>Payment failed.</h2>";
    echo "<pre>" . print_r($response, true) . "</pre>";
}
?>
