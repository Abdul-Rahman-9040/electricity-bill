<?php
include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $phone = $_POST["phone"];
    $service = isset($_POST["service"]) ? $_POST["service"] : ''; // Check if "service" is set
    $email = $_POST["email"];
    $message = $_POST["message"];

    // Insert data into the database
    $sql = "INSERT INTO contact (name, phone, service, email, message) VALUES ('$name', '$phone', '$service', '$email', '$message')";

    if ($conn->query($sql) === TRUE) {
        // Retrieve the name from the database
        $retrieveSql = "SELECT name, message FROM contact ORDER BY 1 DESC LIMIT 1"; // Use "ORDER BY 1" to order by the first column
        $result = $conn->query($retrieveSql);

        if ($result) {
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $name = $row["name"];
                $message = $row["message"];
                $conn->close();
                header("Location: contactmessage.php?name=$name");
                exit();
            } else {
                echo "Error: No rows found in the database for the inserted data";
            }
        } else {
            echo "Error retrieving data from the database: ".$conn->error;
        }
    } else {
        echo "Error inserting data into the database: ".$conn->error;
    }
}
?>
