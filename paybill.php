<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="assets/css/sidenav.css">
</head>
<body>
  <div class="sidebar">
    <a href="customer_dashboard.php"><i class="fa fa-home" style="font-size:24px"></i>&emsp;Home</a>
    <a href="viewbill.php"><i class='fa fa-history' style='font-size:24px'></i>&emsp;Bills</a>
    <a href="paybill.php"><i class='fa fa-credit-card' style='font-size:24px'></i>&emsp;Pay Bill</a>
    <a href="index.html"><i class='fa fa-sign-out' style='font-size:24px'></i>&emsp;Logout</a>  
  </div>
  <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }
        .welcome-message {
            background-color: #3498db;
            color: #fff;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        .welcome-message p {
            font-size: 24px;
        }
        td{
          background-color: #3498db;
        }
    </style>
  <div class="content">
  <div class="welcome-message">
  <?php
session_start();
include 'conn.php';

// Check if the user is logged in as a user
if (isset($_SESSION["user_id"]) && $_SESSION["user_type"] == "user") {
    $name = $_SESSION["username"];
    $userType = $_SESSION["user_type"];


    // Fetch data from both tables using 'name'
    $combinedSql = "SELECT 
        'Consumer' AS data_source, 
        id,
        customer_id, 
        units_consumed, 
        bill, 
        selectedMonth, 
        payment_status
    FROM 
        consumer
    WHERE
        name = '$name' AND
        payment_status='pending'
  ";

    $combinedResult = $conn->query($combinedSql);

    if ($combinedResult->num_rows > 0) {
        echo "<h2>Electricity Bills</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Data Source</th><th>Customer ID</th><th>Units Consumed</th><th>Bill(₹)</th><th>Month</th><th>Pay Bill</th></tr>";

        while ($combinedRow = $combinedResult->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $combinedRow["data_source"] . "</td>";
            echo "<td>" . $combinedRow["customer_id"] . "</td>";
            echo "<td>" . $combinedRow["units_consumed"] . "</td>";
            echo "<td>" . $combinedRow["bill"] . "</td>";
            echo "<td>" . $combinedRow["selectedMonth"] . "</td>";
            echo "<td><a href='payment.php?id=" . $combinedRow["id"] . "'>Make Payment</a></td>";
        
            echo "</tr>";
        }
        

        echo "</table>";
    } else {
        echo "No Pending Bills.";
    }

    // Close the database connection
    $conn->close();
} else {
    // Redirect to login page if not logged in as a user
    header("Location: login.html");
    exit();
}
?>





      

    </div>
    
  </div>
  <p style="padding-top:400px"></p>

</body>
</html>
