<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Admin Login</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <style>
    /* Style for the background */
    body {
      margin: 0;
      padding: 0;
      height: 100vh;
      background-image: url('admin_image/blood-cells.jpg'); /* Use your image URL here */
      background-size: cover;
      background-position: center;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: Arial, sans-serif;
    }

    /* Overlay effect */
    .overlay {
      background-color: rgba(0, 0, 0, 0.5);
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.4);
    }

    /* Form container */
    .login-form {
      width: 350px;
      background-color: rgba(255, 255, 255, 0.8);
      padding: 20px;
      border-radius: 10px;
    }

    /* Form input fields */
    .login-form input {
      margin-bottom: 15px;
      padding: 10px;
      width: 100%;
      border: 1px solid #ddd;
      border-radius: 5px;
    }

    /* Button styling */
    .login-form button {
      width: 100%;
      padding: 10px;
      background-color: #007bff;
      border: none;
      border-radius: 5px;
      color: white;
      font-weight: bold;
    }

    .login-form button:hover {
      background-color: #0056b3;
    }

    /* Admin Login heading */
    .heading {
      font-size: 24px;
      text-align: center;
      margin-bottom: 10px;
      color: white;
    }

    .subheading {
      text-align: center;
      color: #ffffffb3;
      margin-bottom: 20px;
    }
  </style>
</head>

<body>

  <div class="overlay">
    <div class="heading">ADMIN Login</div>
    <div class="subheading">Fill in the form below to get instant access</div>

    <div class="login-form">
      <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="text" name="username" placeholder="User Name" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Submit</button>
      </form>
      <?php
  // Include the connection to the database
  include 'conn.php'; 

  // Check if the form is submitted
  if (isset($_POST["login"])) {
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);

    // Query to check if the user exists
    $sql = "SELECT * FROM admin_info WHERE admin_username='$username' AND admin_password='$password'";
    $result = mysqli_query($conn, $sql) or die("Query failed.");

    // Check if the user is found
    if (mysqli_num_rows($result) > 0) {
      session_start(); // Start the session
      $_SESSION['loggedin'] = true; // Set session variables
      $_SESSION["username"] = $username;

      // Redirect to dashboard page
      header("Location: dashboard.php");
      exit(); // Always call exit after header to stop further script execution
    } else {
      // If username and password don't match
      echo '<div class="alert">Username and Password are not matched!</div>';
    }
  }
?>

    </div>
  </div>

</body>
</html>
