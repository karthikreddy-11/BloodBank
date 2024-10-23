<?php
$active = 'contact'; 
include 'head.php'; 
?>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>

<?php
// Database connection (included directly)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blood_donation";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST["send"])) {
    // Retrieve form data safely
    $name = $_POST['fullname'];
    $number = $_POST['contactno'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    
    // Prepare SQL query with prepared statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO contact_query (query_name, query_mail, query_number, query_message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $number, $message);
    
    // Execute and provide feedback
    if ($stmt->execute()) {
        echo '<div class="alert alert-success alert_dismissible"><b><button type="button" class="close" data-dismiss="alert">&times;</button></b><b>Query Sent, We will contact you shortly. </b></div>';
    } else {
        echo '<div class="alert alert-danger alert_dismissible"><b><button type="button" class="close" data-dismiss="alert">&times;</button></b><b>Failed to send query. Please try again.</b></div>';
    }
    
    $stmt->close();
}
?>

<div id="page-container" style="margin-top:50px; position: relative;min-height: 84vh;">
  <div class="container">
    <div id="content-wrap" style="padding-bottom:50px;">
      <h1 class="mt-4 mb-3">Contact</h1>
      <div class="row">
        <!-- Contact Form Section -->
        <div class="col-lg-8 mb-4">
          <h3>Send us a Message</h3>
          <form name="sentMessage" method="post">
            <div class="control-group form-group">
              <div class="controls">
                <label>Full Name:</label>
                <input type="text" class="form-control" id="name" name="fullname" required>
              </div>
            </div>
            <div class="control-group form-group">
              <div class="controls">
                <label>Phone Number:</label>
                <input type="tel" class="form-control" id="phone" name="contactno" required pattern="[0-9]{10}" title="Please enter a valid 10-digit phone number.">
              </div>
            </div>
            <div class="control-group form-group">
              <div class="controls">
                <label>Email Address:</label>
                <input type="email" class="form-control" id="email" name="email" required>
              </div>
            </div>
            <div class="control-group form-group">
              <div class="controls">
                <label>Message:</label>
                <textarea rows="10" cols="100" class="form-control" id="message" name="message" required maxlength="999" style="resize:none"></textarea>
              </div>
            </div>
            <button type="submit" name="send" class="btn btn-primary">Send Message</button>
          </form>
        </div>

        <!-- Contact Details Section -->
        <div class="col-lg-4 mb-4">
          <h2>Contact Details</h2>
          <?php
          // Fetch contact details from the database
          $sql = "SELECT * FROM contact_info";
          $result = $conn->query($sql);
          
          if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
          ?>
          <br>
          <p><h4>Address :</h4><?php echo htmlspecialchars($row['contact_address'], ENT_QUOTES, 'UTF-8'); ?></p>
          <p><h4>Contact Number :</h4><?php echo htmlspecialchars($row['contact_phone'], ENT_QUOTES, 'UTF-8'); ?></p>
          <p><h4>Email :</h4><a href="mailto:<?php echo htmlspecialchars($row['contact_mail'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($row['contact_mail'], ENT_QUOTES, 'UTF-8'); ?></a></p>
          <?php
              }
          } else {
              echo '<p>No contact information available.</p>';
          }
          ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
