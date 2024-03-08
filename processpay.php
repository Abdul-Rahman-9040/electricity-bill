<?php
// PHP processing code (processpay.php)
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure to validate and sanitize user input
    $consumerId = $_POST['id'];
    $cardNumber = $_POST['cardNumber'];
    $expiry = $_POST['expiry'];
    $cvv = $_POST['cvv'];
    $amount = $_POST['amount'];

    // Check if the consumerId is received
    if (!$consumerId) {
        echo "Error: Missing consumerId";
        exit();
    }

    // Verify card details with the carddetails table
    $verifyCardQuery = "SELECT * FROM carddetails WHERE cardNumber = ? AND expiry = ? AND cvv = ?";
    $verifyStmt = $conn->prepare($verifyCardQuery);
    $verifyStmt->bind_param('sss', $cardNumber, $expiry, $cvv);
    $verifyStmt->execute();

    if ($verifyStmt->errno) {
        echo "Error executing verifyCardQuery: " . $verifyStmt->error;
        exit();
    }

    $verifyResult = $verifyStmt->get_result();

    if ($verifyResult->num_rows > 0) {
        // Card details are valid
        $cardDetails = $verifyResult->fetch_assoc();
        $currentBalance = $cardDetails['balance'];

        if ($currentBalance >= $amount) {
            // Sufficient balance, update payment status and deduct the amount
            $newBalance = $currentBalance - $amount;

            // Update payment status in the consumer table
            $updatePaymentQuery = "UPDATE consumer SET payment_status = 'paid' WHERE id = ?";
            $updateStmt = $conn->prepare($updatePaymentQuery);
            $updateStmt->bind_param('i', $consumerId);
            $updateStmt->execute();

            if ($updateStmt->errno) {
                echo "Error updating payment status: " . $updateStmt->error;
                exit();
            }

            // Deduct the amount from the card's balance in carddetails table
            $updateBalanceQuery = "UPDATE carddetails SET balance = ? WHERE cardNumber = ?";
            $updateBalanceStmt = $conn->prepare($updateBalanceQuery);
            $updateBalanceStmt->bind_param('ss', $newBalance, $cardNumber);
            $updateBalanceStmt->execute();

            if ($updateBalanceStmt->errno) {
                echo "Error updating balance: " . $updateBalanceStmt->error;
                exit();
            }

            // Close the database connection
            $conn->close();

            echo '<script>alert("Payment successful!");';
            echo 'window.location.href = "customer_dashboard.php";</script>';
            exit();
        } else {
            // Insufficient balance
            echo '<script>alert("Insufficient balance. Please top up your card.");';
            echo 'window.location.href = "payment.php?id=' . $consumerId . '";</script>';
            exit();
        }
    } else {
        // Card details are not valid
        echo '<script>alert("Invalid card details. Please try again.");';
        echo 'window.location.href = "payment.php?id=' . $consumerId . '";</script>';
        exit();
    }
} else {
    // Handle non-POST requests
    header('Location: error.php');
    exit();
}
?>
