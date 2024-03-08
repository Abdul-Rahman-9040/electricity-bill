<?php
require_once("conn.php"); // Assuming you have a file with database connection (conn.php)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $user_type = $_POST["user_type"];

    // Check if email already exists in the registered table
    $checkEmailRegisteredQuery = "SELECT COUNT(*) as count FROM registered WHERE email = '$email'";
    $resultRegistered = $conn->query($checkEmailRegisteredQuery);
    $rowRegistered = $resultRegistered->fetch_assoc();

    if ($rowRegistered['count'] > 0) {
        echo "<script>alert('Email already exists in the registered table. Please use a different email address.');</script>";
        echo "<script>window.location.href = 'login.html'</script>";
        exit(); // Stop further execution if email exists in the registered table
    }

    // Handling file upload
    $target_dir = "uploads/"; // Specify the directory where you want to store the uploaded files
    $target_file = $target_dir . basename($_FILES["file"]["name"]);

    // Move uploaded file to the specified directory
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        echo "The file " . htmlspecialchars(basename($_FILES["file"]["name"])) . " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }

    // Perform database insertion into temp_reg table
    $insertUserQuery = "INSERT INTO temp_reg (name, email, password, user_type, file_path) 
                       VALUES ('$name', '$email', '$password', '$user_type', '$target_file')";

    if ($conn->query($insertUserQuery) === TRUE) {
        echo "<script>alert('Account will be approved soon by MESCOM!');</script>";
        echo "<script>window.location.href = 'login.html'</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
