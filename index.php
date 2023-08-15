<?php 
session_start(); 
   //please NOTE there is an addadmin.php file for easy addition of admin with hashpassword to database
?>
<?php 
     include 'checksession.php'; 
     loginStatus(); 
 ?> 
<!DOCTYPE html>
<html>
  <head>
    <title>BnB example system</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  </head>
  <body>
  <div class="container">  
  <h1>BIT608 Web Programming </h1>
  </div>
  <div class="container">
    <h2>Assessment case study web application temporary launch page</h2></br>
  </div>
      <div class="container">
      <ul>
        <li><a href="original_template/">Original Template example</a>
        <li><a href="converted_template/">Converted Template example</a>
        <li><a href="listcustomers.php">Customer listing</a>
        <li><a href="listrooms.php">Rooms listing</a>
        <li><a href="listbookings.php">Bookings listing</a>
        <li><a href="login.php">Login</a>
      </ul>
      </div>
  </body>
</html>