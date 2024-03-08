<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="assets/css/sidenav.css">
</head>
<body>
  <div class="sidebar">
    <a href="super_dashboard.php"><i class="fa fa-home" style="font-size:24px"></i>&emsp;Home</a>
    <a href="approve.php"><i class="fa fa-check" style="font-size:24px"></i>&emsp;Approve</a>
    <a href="viewuser.php"><i class='fa fa-user' style='font-size:24px'></i>&emsp;View Users</a>

    <a href="login.html"><i class='fa fa-sign-out' style='font-size:24px'></i>&emsp;Logout</a>  
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
    <h1>Approve Customers</h1>
  <?php
// Include your connection file
require_once 'conn.php';

// Display data from temp_reg
$sql = "SELECT * FROM temp_reg";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>File Path</th>
                <th>Action</th>
            </tr>";

    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["name"] . "</td>
                <td>" . $row["email"] . "</td>
                <td><a href='" . $row["file_path"] . "' target='_blank'>View PDF</a></td>
                <td>
                    <form method='post' action='process.php'>
                        <input type='hidden' name='email' value='" . $row["email"] . "'>
                        <button type='submit' name='approve'>Approve</button>
                        <button type='submit' name='reject'>Reject</button>
                    </form>
                </td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "<h1>No New Applications.</h1>";
}

// Close the connection at the end of the script
$conn->close();
?>



      

    </div>
    
  </div>
  <p style="padding-top:400px"></p>

</body>
</html>
