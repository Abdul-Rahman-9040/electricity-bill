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
    </style>
  <div class="content">
  <div class="welcome-message">
  <img src="assets/img/user.png" alt="" srcset="" width="250px" height="250px">
  <?php
session_start();

// Check if user is logged in as super user
if (isset($_SESSION["user_id"]) && $_SESSION["user_type"] == "super") {
    $username = $_SESSION["username"];
    $userType = $_SESSION["user_type"];
    // Display username and user type
    echo "<h1>Welcome, $username <br></h1>";
    echo "Super User";
    
    // Rest of your super user dashboard code goes here
} else {
    // Redirect to login page if not logged in as super user
    header("Location: login.html");
    exit();
}
?>


      

    </div>
    
  </div>
  <p style="padding-top:400px"></p>

</body>
</html>
