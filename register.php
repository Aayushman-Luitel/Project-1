<?php
include 'config.php';

if(isset($_POST['submit'])){

   $name = trim($_POST['name']);
   $raw_email = trim($_POST['email']); // Get raw email first
   $password = $_POST['password'];
   $cpassword = $_POST['cpassword'];
   $user_type = 'user';

   $messages = [];

   // Name validation (3-20 LETTERS excluding spaces)
   $clean_name = preg_replace('/\s+/', '', $name); // Remove all spaces
   $name_length = strlen($clean_name);
   
   if($name_length < 3 || $name_length > 20) {
       $messages[] = 'Name must contain 3-20 letters (spaces ignored)';
   }
   if(!preg_match('/^[A-Za-z ]+$/', $name)) {
       $messages[] = 'Name can only contain letters and spaces';
   }

   // Email validation (strict format)
   if(!preg_match('/^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[a-zA-Z]{2,}$/', $raw_email)) {
      $messages[] = 'Invalid email format (example: c@c.com)';
  }

  // Password validation
  if(strlen($password) > 20) {
      $messages[] = 'Password cannot exceed 20 characters';
  }
  if($password !== $cpassword) {
      $messages[] = 'Passwords do not match!';
  }

  // Proceed if no errors
  if(empty($messages)){
      // Sanitize inputs AFTER validation
      $name = mysqli_real_escape_string($conn, $name);
      $email = mysqli_real_escape_string($conn, $raw_email); // Sanitize email here

      // Check existing user
      $stmt = mysqli_prepare($conn, "SELECT email FROM users WHERE email = ?");
      mysqli_stmt_bind_param($stmt, "s", $email);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_store_result($stmt);

      if(mysqli_stmt_num_rows($stmt) > 0){
          $messages[] = 'User already exists!';
      } else {
          // Insert new user
          $password_hash = password_hash($password, PASSWORD_DEFAULT);
          $insert_stmt = mysqli_prepare($conn, "INSERT INTO users (name, email, password, user_type) VALUES (?, ?, ?, ?)");
          mysqli_stmt_bind_param($insert_stmt, "ssss", $name, $email, $password_hash, $user_type);
          mysqli_stmt_execute($insert_stmt);

          $messages[] = 'Registered successfully!';
          header('Location: login.php');
          exit();
      }
  }

   $message = $messages; // For existing message display
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register</title>
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>



<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>
   
   <div class="form-container">
   <form action="" method="post" id="myForm">
      <h3>register now</h3>

      <input type="text" name="name" id="name" placeholder="Enter your name" 
       pattern="[A-Za-z ]{3,}" 
       title="3-20 letters (spaces allowed b
       ut not counted)"
       required class="box">

       <input type="email" name="email" placeholder="Enter your email" 
       pattern="[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[a-zA-Z]{2,}" 
       title="Email must be in format: c@c.com" 
       required class="box">

      <input type="password" name="password" placeholder="Enter your password" 
             minlength="6" maxlength="20" 
             title="Password must be 6-20 characters" 
             required class="box">

      <input type="password" name="cpassword" placeholder="Confirm your password" 
             minlength="6" maxlength="20" 
             title="Password must be 6-20 characters" 
             required class="box">

      <input type="hidden" name="user_type" value="user"> 

      <input type="submit" name="submit" value="register now" class="btn">
      <p>Already have an account? <a href="login.php">Login now</a></p>
   </form>
</div>
<script src="js/error.js"></script>

</body>
</html>