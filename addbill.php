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
 

<!DOCTYPE html>
<html>
<head>
    <title>Customer Details</title>
</head>
<body>

    <h2>Enter Customer ID to Add Bill</h2>
    
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>"  id="searchForm">
        Customer ID: <input type="text" name="customerId">
        <input type="submit" value="search" id="searchButton">
    </form>
    <br><br>
    <style>
        /* style.css */

/* Hidden class to initially hide elements */
.hidden {
    display: none;
}

/* Style for input fields */
#numberOfUnits,
#selectedMonth {
    width: 150px;
    padding: 5px;
    margin: 5px 0;
}

    </style>
   <?php

// Include the database connection file
include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the customer ID from the form
    $customerId = $_POST["customerId"];

    // Prepare and execute SQL query
    $sql = "SELECT customer_id, name, email FROM registered WHERE customer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customerId);
    $stmt->execute();
    $stmt->store_result();

    // Check if customer ID exists
    if ($stmt->num_rows > 0) {
        // Bind result variables
        $stmt->bind_result($id, $name, $email);

        // Display customer details in a table
        echo "<table border='1'>";
        echo "<tr><th>Customer ID</th><th>Name</th><th>Email</th></tr>";

        while ($stmt->fetch()) {
            echo "<tr>";
            echo "<td>" . $id . "</td>";
            echo "<td>" . $name . "</td>";
            echo "<td>" . $email . "</td>";
            echo "</tr>";
        }

        echo "</table>";
        echo "<br><br>";

        echo "<table>";
        echo "<tr><td>";
        echo "<form method='post' action=''>";
        echo "<label for='numberOfUnits' id='unitsLabel' class='hidden'>Number of Units:</label>";
        echo "</td><td>";
        echo "<input type='number' name='numberOfUnits' id='numberOfUnits' class='hidden' required>";
        echo "</td></tr>";

        echo "<tr><td>";
        echo "<label for='selectedMonth' id='monthLabel' class='hidden'>Month:</label>";
        echo "</td><td>";
        echo "<select name='selectedMonth' id='selectedMonth' class='hidden' required>";
        echo "<option value='January'>January</option>";
        echo "<option value='February'>February</option>";
        echo "<option value='March'>March</option>";
        echo "<option value='April'>April</option>";
        echo "<option value='May'>May</option>";
        echo "<option value='June'>June</option>";
        echo "<option value='July'>July</option>";
        echo "<option value='August'>August</option>";
        echo "<option value='September'>September</option>";
        echo "<option value='October'>October</option>";
        echo "<option value='November'>November</option>";
        echo "<option value='December'>December</option>";
        echo "</select>";
        echo "</td></tr>";

        echo "<tr><td>";
        echo "<input type='hidden' name='customerId' value='$customerId'>"; // Hidden field to pass customer ID
        echo "</td><td>";
        echo "<input type='submit' value='Calculate Bill'>";
        echo "</form>";
        echo "</td></tr>";
        echo "</table>";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Check if the form is submitted and the required keys are set in $_POST
            if (isset($_POST["numberOfUnits"], $_POST["selectedMonth"])) {
                // Retrieve values from the form
                $numberOfUnits = $_POST["numberOfUnits"];
                $selectedMonth = $_POST["selectedMonth"];

                // Assuming a rate per unit (you should replace this with the actual rate applicable)
                $freeUnitsLimit = 200;
                $ratePerUnitFree = 0.0; // Example rate for the first 200 units (free)
                $ratePerUnitAbove200 = 0.0820; // Example rate for units above 200 (in paise)

                // Calculate the bill
                if ($numberOfUnits <= $freeUnitsLimit) {
                    // First 200 units are free
                    $electricityBill = 0.0;
                } else {
                    // Calculate for units above 200
                    $unitsAbove200 = $numberOfUnits - $freeUnitsLimit;
                    $electricityBill = $unitsAbove200 * $ratePerUnitAbove200;
                }
                echo "<br><br>";
                // Display the result
                echo "<table border='1'>";
                echo "<tr><th>Details</th><th>Value</th></tr>";
                echo "<tr><td>Number of Units Consumed:</td><td>$numberOfUnits</td></tr>";
                echo "<tr><td>Selected Month:</td><td>$selectedMonth</td></tr>";
                echo "<tr><td>Electricity Bill:</td><td>₹ $electricityBill</td></tr>";
                echo "</table>";
                echo "<br><br>";
                // Add a div with a class initially set to hidden
                echo "<button type='button' onclick='generateBillAndInsert()'>Generate Bill</button>";


                $insertSql = "INSERT INTO consumer (customer_id, name, email, units_consumed, bill, payment_status, selectedMonth) VALUES (?, ?, ?, ?, ?, 'pending', ?)";
                $insertStmt = $conn->prepare($insertSql);
                $insertStmt->bind_param("isssds", $id, $name, $email, $numberOfUnits, $electricityBill, $selectedMonth);
                $insertStmt->execute();
                $insertStmt->close();
                
                // Here, you might want to store the result in a database or perform additional actions
            }
        }
        // JavaScript to show input fields and the generateBillDiv
        echo "<script>";
        echo "document.getElementById('unitsLabel').classList.remove('hidden');";
        echo "document.getElementById('numberOfUnits').classList.remove('hidden');";
        echo "document.getElementById('monthLabel').classList.remove('hidden');";
        echo "document.getElementById('selectedMonth').classList.remove('hidden');";
        echo "document.getElementById('addbill').classList.remove('hidden');";
        echo "</script>";
        echo "<br><br>";
    } else {
        echo "Customer ID not found.";
    }
?>
<script>
    function generateBillAndInsert() {
        alert('Bill generated for customer_id: <?php echo $customerId; ?>');
    }
</script>
<?php
    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>


 


</body>
</html>



   
</body>
</html>



      

    </div>
    
  </div>
  <p style="padding-top:400px"></p>

</body>
</html>
