<?php
// Include your connection file
require_once 'conn.php';

if (isset($_POST['approve'])) {
    $email = $_POST['email'];

    // Generate a unique 13-digit customer_id
    do {
        $customer_id = mt_rand(1000000000000, 9999999999999);
        $check_sql = "SELECT COUNT(*) as count FROM registered WHERE customer_id = '$customer_id'";
        $result = $conn->query($check_sql);
        $row = $result->fetch_assoc();
        $exists = $row['count'] > 0;
    } while ($exists);

    // Insert data into registered table
    $select_sql = "SELECT name, email, file_path, user_type, password FROM temp_reg WHERE email = '$email'";
    $select_result = $conn->query($select_sql);

    if ($select_result->num_rows > 0) {
        $row = $select_result->fetch_assoc();
        $name = $row["name"];
        $file_path = $row["file_path"];
        $user_type = $row["user_type"];
        $password = $row["password"];

        $insert_sql = "INSERT INTO registered (customer_id, name, email, user_type, password, file_path)
                       VALUES ('$customer_id', '$name', '$email', '$user_type', '$password', '$file_path')";

        if ($conn->query($insert_sql) === TRUE) {
            // Delete data from temp_reg
            $delete_sql = "DELETE FROM temp_reg WHERE email = '$email'";
            $conn->query($delete_sql);
            echo "<script>alert('Application approved successfully with customer_id: $customer_id');</script>";
            echo "<script>window.location.href = 'super_dashboard.php'</script>";

        } else {
            echo "Error in approving Application: " . $conn->error;
        }
    } else {
        echo "Error fetching data from temp_reg.";
    }
} elseif (isset($_POST['reject'])) {
    $email = $_POST['email'];
    // Delete data from temp_reg
    $delete_sql = "DELETE FROM temp_reg WHERE email = '$email'";

    if ($conn->query($delete_sql) === TRUE) {
        echo "<script>alert('Application Rejected Succesfully');</script>";
        echo "<script>window.location.href = 'super_dashboard.php'</script>";


    } else {
        echo "Error rejecting record: " . $conn->error;
    }
}

// Close the connection at the end of the script
$conn->close();
?>
