<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="assets/css/sidenav.css">
</head>
<body>
  <div class="sidebar">
    <a href="customer_dashboard.php"><i class="fa fa-home" style="font-size:24px"></i>&emsp;Home</a>
    <a href="viewbill.php"><i class='fa fa-eye' style='font-size:24px'></i>&emsp;View Bill</a>
    <a href="paybill.php"><i class='fa fa-credit-card' style='font-size:24px'></i>&emsp;Pay Bill</a>
    <a href="complaint.php"><i class='fa fa-pencil-square-o' style='font-size:24px'></i>&emsp;Complaint</a>
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
        #formContainer {
            border: 2px solid #ccc;
            padding: 20px;
            display: inline-block;
            text-align:left;

        }
        form {
            display: inline-block;
        }
        label {
            display: block;
            margin-bottom: 10px;
        }
        input {
            margin-bottom: 15px;   
        }
        #cardNumber,
    #expiry,
    #cvv,
    #upiNumber {
       width: 300px;
    }
        #cardFields, #upiField, #submitButton {
            display: none;
        }
        #submitButton {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        #submitButton:hover {
            background-color: #45a049;
        }
    </style>
  <div class="content">
  <div class="welcome-message">
<h2>Select Payment Method</h2>
<div id="formContainer">
<form id="paymentForm" method="post" action="processpay_card.php?id=<?php echo $consumerId; ?>">
    <label>
        <input type="radio" name="paymentMethod" value="card" id="cardRadio" required> Credit/Debit Card
    </label>
    <label>
        <input type="radio" name="paymentMethod" value="upi" id="upiRadio" required> UPI
    </label>

    <div id="cardFields" style="display: none;">
        <label for="cardNumber">Card Number:</label>
        <input type="text" id="cardNumber" name="cardNumber" placeholder="1111222233334444" required>
        <br>
        <label for="cvv">CVV:</label>
        <input type="text" id="cvv" name="cvv" placeholder="2451" required>
        <br>
        <label for="expiry">Expiry Date:</label>
        <input type="text" id="expiry" name="expiry" placeholder="2025" required>
        <br>
        <?php
// Include your conn.php file for database connection
include('conn.php');

// Get the ID from the URL or form data
$id = $_REQUEST['id'];

// Prepare and execute the SQL query to get the amount based on id
$sql = "SELECT bill FROM consumer WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id); // Assuming id is an integer, adjust the type accordingly
$stmt->execute();
$stmt->bind_result($amount);

// Fetch the result
if ($stmt->fetch()) {
    // Display the amount
    echo "<h1>Bill Amount: $amount</h1>";
} else {
    // If id is not found, return an error or a default value
    echo "Error: ID not found";
}

// Close the database connection
$stmt->close();
$conn->close();
?>
    </div>

    <div id="upiField" style="display: none;">
        <label for="upiNumber">UPI Number:</label>
        <input type="text" id="upiNumber" name="upiNumber" placeholder="Enter UPI address" required>
        <?php
// Include your conn.php file for database connection
include('conn.php');

// Get the ID from the URL or form data
$id = $_REQUEST['id'];

// Prepare and execute the SQL query to get the amount based on id
$sql = "SELECT bill FROM consumer WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id); // Assuming id is an integer, adjust the type accordingly
$stmt->execute();
$stmt->bind_result($amount);

// Fetch the result
if ($stmt->fetch()) {
    // Display the amount
    echo "<h1>Bill Amount: $amount</h1>";
} else {
    // If id is not found, return an error or a default value
    echo "Error: ID not found";
}

// Close the database connection
$stmt->close();
$conn->close();
?>
    </div>

    <br>


    <button type="button" onclick="makePayment()">Make Payment</button>
</form>
<script>
    document.getElementById('cardRadio').addEventListener('change', function () {
        document.getElementById('cardFields').style.display = 'block';
        document.getElementById('upiField').style.display = 'none';
    });

    document.getElementById('upiRadio').addEventListener('change', function () {
        document.getElementById('upiField').style.display = 'block';
        document.getElementById('cardFields').style.display = 'none';
    });

    function makePayment() {
        var paymentMethod = document.querySelector('input[name="paymentMethod"]:checked').value;
        var form = document.getElementById('paymentForm');
        var idParam = getURLParameter('id');

        if (paymentMethod === 'card') {
            form.action = 'processpay_card.php?id=' + idParam;
        } else if (paymentMethod === 'upi') {
            form.action = 'processpay_upi.php?id=' + idParam;
        }

        form.submit();
    }

    function getURLParameter(name) {
        var urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }
</script>



</div>









      

    </div>
    
  </div>
  <p style="padding-top:400px"></p>

</body>
</html>
