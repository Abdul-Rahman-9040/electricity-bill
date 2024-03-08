<?php
// PHP processing code (processpay_upi.php)
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Ensure to validate and sanitize user input
        $upiNumber = $_POST['upiNumber'];

        // Retrieve the id from the URL
        $consumerId = isset($_GET['id']) ? $_GET['id'] : null;

        if ($consumerId === null) {
            throw new Exception("Error: Missing consumerId");
        }

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

        // Fetch the balance from upidetails table
        $balanceQuery = "SELECT balance FROM upidetails WHERE upiNumber = ?";
        $balanceStmt = $conn->prepare($balanceQuery);
        $balanceStmt->bind_param('s', $upiNumber);
        $balanceStmt->execute();
        $balanceResult = $balanceStmt->get_result();

        if ($balanceResult->num_rows > 0) {
            $balanceRow = $balanceResult->fetch_assoc();
            $currentBalance = $balanceRow['balance'];

            if ($currentBalance >= $billAmount) {
                // Subtract the bill amount from the balance
                $newBalance = $currentBalance - $billAmount;

                // Update the balance in upidetails table
                $updateBalanceQuery = "UPDATE upidetails SET balance = ? WHERE upiNumber = ?";
                $updateBalanceStmt = $conn->prepare($updateBalanceQuery);
                $updateBalanceStmt->bind_param('ds', $newBalance, $upiNumber);
                $updateBalanceStmt->execute();

                if ($updateBalanceStmt->errno) {
                    throw new Exception("Error updating balance: " . $updateBalanceStmt->error);
                }

                // Update payment status in the consumer table
                $updatePaymentQuery = "UPDATE consumer SET payment_status = 'paid' WHERE id = ?";
                $updateStmt = $conn->prepare($updatePaymentQuery);
                $updateStmt->bind_param('i', $consumerId);
                $updateStmt->execute();

                if ($updateStmt->errno) {
                    throw new Exception("Error updating payment status: " . $updateStmt->error);
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
            // UPI details not found
            throw new Exception("Error: UPI details not found");
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
