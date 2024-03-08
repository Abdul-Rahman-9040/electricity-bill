<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="assets/css/sidenav.css">
</head>
<body>
  <div class="sidebar">
    <a href="admin_dashboard.php"><i class="fa fa-home" style="font-size:24px"></i>&emsp;Home</a>
    <a href="adminviewuser.php"><i class='fa fa-user' style='font-size:24px'></i>&emsp;View Users</a>
    <a href="addbill.php"><i class='fa fa-plus' style='font-size:24px'></i>&emsp;Add Bill</a>
    <a href="pendingbill.php"><i class='fa fa-clock-o' style='font-size:24px'></i>&emsp;Pending Bills</a>
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
  <h1>Pending Bills</h1>
  <?php
// Include the database connection file
include 'conn.php';

// Fetch data from the consumer table where payment_status is 'no'
$sql = "SELECT customer_id, name, email, units_consumed FROM consumer WHERE payment_status = 'pending'";
$result = $conn->query($sql);

// Check if there are any rows returned
if ($result->num_rows > 0) {
    // Display the data in a table format
    echo "<table border='1'>";
    echo "<tr><th>Customer ID</th><th>Name</th><th>Email</th><th>Units Consumed</th></tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["customer_id"] . "</td>";
        echo "<td>" . $row["name"] . "</td>";
        echo "<td>" . $row["email"] . "</td>";
        echo "<td>" . $row["units_consumed"] . "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "No Bills.";
}

// Close the database connection
$conn->close();
?>

  


      

    </div>
    
  </div>
  <p style="padding-top:400px"></p>

</body>
</html>
