<?php
// Include your conn.php file for database connection
include('conn.php');

// Get the ID from the URL or form data
$id = $_REQUEST['id'];

// Prepare and execute the SQL query to get the amount based on id
$sql = "SELECT amount FROM consumer WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id); // Assuming id is an integer, adjust the type accordingly
$stmt->execute();
$stmt->bind_result($amount);

// Fetch the result
if ($stmt->fetch()) {
    // Display the amount
    echo "Bill Amount: $amount";
} else {
    // If id is not found, return an error or a default value
    echo "Error: ID not found";
}

// Close the database connection
$stmt->close();
$conn->close();
?>
