<?php
// PHP processing code (processpay.php)
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Ensure to validate and sanitize user input
        $cardNumber = $_POST['cardNumber'];
        $expiry = $_POST['expiry'];
        $cvv = $_POST['cvv'];

        // Retrieve the id from the URL
        $consumerId = isset($_GET['id']) ? $_GET['id'] : null;

        if ($consumerId === null) {
            throw new Exception("Error: Missing consumerId");
        }

        // Verify card details with the carddetails table
        $verifyCardQuery = "SELECT * FROM carddetails WHERE cardNumber = ? AND expiry = ? AND cvv = ?";
        $verifyStmt = $conn->prepare($verifyCardQuery);
        $verifyStmt->bind_param('sss', $cardNumber, $expiry, $cvv);
        $verifyStmt->execute();

        if ($verifyStmt->errno) {
            throw new Exception("Error executing verifyCardQuery: " . $verifyStmt->error);
        }

        $verifyResult = $verifyStmt->get_result();

        if ($verifyResult->num_rows > 0) {
            // Card details are valid
            $cardDetails = $verifyResult->fetch_assoc();
            $currentBalance = $cardDetails['balance'];

            // Fetch the bill amount from the consumer table
            $billAmountQuery = "SELECT bill FROM consumer WHERE id = ?";
            $billAmountStmt = $conn->prepare($billAmountQuery);
            $billAmountStmt->bind_param('i', $consumerId);
            $billAmountStmt->execute();
            $billAmountResult = $billAmountStmt->get_result();

            if ($billAmountResult->num_rows > 0) {
                $billAmountRow = $billAmountResult->fetch_assoc();
                $billAmount = $billAmountRow['bill'];
            } else {
                throw new Exception("Error fetching bill amount from consumer table.");
            }

            if ($currentBalance >= $billAmount) {
                // Subtract the bill amount from the card's balance
                $newBalance = $currentBalance - $billAmount;

                // Update payment status in the consumer table
                $updatePaymentQuery = "UPDATE consumer SET payment_status = 'paid' WHERE id = ?";
                $updateStmt = $conn->prepare($updatePaymentQuery);
                $updateStmt->bind_param('i', $consumerId);
                $updateStmt->execute();

                if ($updateStmt->errno) {
                    throw new Exception("Error updating payment status: " . $updateStmt->error);
                }

                // Deduct the bill amount from the card's balance in carddetails table
                $updateBalanceQuery = "UPDATE carddetails SET balance = ? WHERE cardNumber = ?";
                $updateBalanceStmt = $conn->prepare($updateBalanceQuery);
                $updateBalanceStmt->bind_param('ss', $newBalance, $cardNumber);
                $updateBalanceStmt->execute();

                if ($updateBalanceStmt->errno) {
                    throw new Exception("Error updating balance: " . $updateBalanceStmt->error);
                }

                // Close the database connection
                $conn->close();

                echo "<script>alert('Payment Successful');</script>";
                echo "<script>window.location.href = 'customer_dashboard.php';</script>";
                exit();
            } else {
                // Insufficient balance
                echo "<script>alert('Insufficient balance.');</script>";
                echo "<script>window.location.href = 'customer_dashboard.php';</script>";
                exit();
            }
        } else {
            // Card details are not valid
            echo "<script>alert('Invalid card details. Please try again.');</script>";
            echo "<script>window.location.href = 'customer_dashboard.php';</script>";
            exit();
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
        exit();
    }
} else {
    // Handle non-POST requests
    header('Location: error.php');
    exit();
}
?>
