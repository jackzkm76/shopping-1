<?php
session_start();
include('includes/config.php');

// Get order_id from GET (e.g., payment-mpesa.php?id=123)
$order_id = isset($_GET['order_id']) ? mysqli_real_escape_string($con, $_GET['order_id']) : '';

// Handle manual confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_payment'])) {
    $order_id = mysqli_real_escape_string($con, $_POST['order_id']);

    // Update order status to 'Paid'
    $update = "UPDATE orders SET orderstatus='Paid' WHERE order_id='$order_id'";
    if (mysqli_query($con, $update)) {
        echo "<script>alert('Thank you! Your payment has been confirmed.');</script>";
        echo "<script>window.location.href='index.php?orders';</script>";
        exit();
    } else {
        echo "<script>alert('Something went wrong. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pay with M-PESA</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 30px;
        }
        .container {
            max-width: 500px;
            background: white;
            margin: auto;
            padding: 40px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .till-box {
            font-size: 24px;
            color: #28a745;
            margin: 25px 0;
            font-weight: bold;
            background: #eafbea;
            padding: 20px;
            border-radius: 8px;
            border: 2px dashed #28a745;
        }
        .paid-btn {
            background: #28a745;
            color: white;
            padding: 15px 30px;
            border: none;
            font-size: 18px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }
        .paid-btn:hover {
            background: #218838;
        }
        img {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <img src="img/m-pesa.png" alt="M-PESA" width="150">
    <h2>Pay via M-PESA Buy Goods</h2>
    <p>Please use your phone to make payment using the M-PESA app or SIM toolkit.</p>

    <div class="till-box">
        TILL Number: <span>123456</span>
    </div>

    <p>After payment, click the button below to confirm your payment.</p>

    <form method="post">
        <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>">
        <button type="submit" name="confirm_payment" class="paid-btn">I HAVE PAID</button>
    </form>
</div>

</body>
</html>
