<?php
require_once 'conn.php';

// Start session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $userType = $_POST["user_type"];

    if (empty($email) || empty($password) || empty($userType)) {
        echo "Please fill in all fields.";
    } else {
        // Perform authentication using database
        $stmt = $conn->prepare("SELECT customer_id, name, email, user_type, password FROM registered WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($customerId, $name, $dbEmail, $dbUserType, $dbPassword);
        $stmt->fetch();
        $stmt->close();

        // Check if user exists and password matches
        if ($dbEmail && $password == $dbPassword) {
            // Set session variables
            $_SESSION["user_id"] = $customerId;
            $_SESSION["username"] = $name;
            $_SESSION["user_type"] = $dbUserType;

            if ($userType == "super" && $dbUserType == "super") {
                // Redirect to super user dashboard
                header("Location: super_dashboard.php");
                exit();
            } elseif ($userType == "admin" && $dbUserType == "admin") {
                // Redirect to admin dashboard
                header("Location: admin_dashboard.php");
                exit();
            } elseif ($userType == "user" && $dbUserType == "user") {
                // Redirect to customer dashboard
                header("Location: customer_dashboard.php");
                exit();
            } else {
                echo "<script>alert('Invalid user type for this account.');</script>";
                echo "<script>window.location.href = 'login.html'</script>";
            }
        } else {
            echo "<script>alert('Invalid email or password.');</script>";
            echo "<script>window.location.href = 'login.html'</script>";
        }
    }
}
?>
