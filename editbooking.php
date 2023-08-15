<?php 
session_start();
include 'checksession.php'; 
checkUser(); 
loginStatus();  
if(!isAdmin()) 
{ 
     //header('Location: http://localhost/motuekaAS2/index.php'); 
     $message = "not enough authority";
     echo "<script type='text/javascript'>alert('$message');
     location.href = 'http://localhost/motuekaAS2/index.php';
     </script>"; 
     exit(); 
}
?>
<!DOCTYPE HTML>
<html><head><title>Edit a booking</title> </head>
<body>

<?php
include "config.php"; //load in any variables
include "cleaninput.php";

$db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
$error=0;
if (mysqli_connect_errno()) {
  echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
  exit; //stop processing the page further
};

//retrieve the bookingid from the URL
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = $_GET['id'];
    if (empty($id) or !is_numeric($id)) {
        echo "<h2>Invalid booking ID</h2>"; //simple error feedback
        exit;
    } 
}
//the data was sent using a formtherefore we use the $_POST instead of $_GET
//check if we are saving data first by checking if the submit button exists in the array
if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Update')) {     
//validate incoming data - only the first field is done for you in this example - rest is up to you do
    
//BookingID (sent via a form to is a string not a number so we try a type conversion!)    
      if (isset($_POST['id']) and !empty($_POST['id']) and is_integer(intval($_POST['id']))) {
        $id = cleanInput($_POST['id']); 
      } else {
        $error++; //bump the error flag
        $msg .= 'Invalid booking ID '; //append error message
        $id = 0;  
      } 
     
//checkindate
      if (isset($_POST['checkindate']) and !empty($_POST['checkindate']) and date($_POST['checkindate'])) { 
        $cho = cleanInput($_POST['checkindate']);
        $checkindate = (strlen($cho)>50)?substr($cho,1,50):$cho;
      }
      else {
        $error++; //bump the error flag
        $msg .= 'Invalid CheckIn Date '; //append eror message
        $checkindate = '';  
      } 
//checkoutdate
      if (isset($_POST['checkoutdate']) and !empty($_POST['checkoutdate']) and date($_POST['checkoutdate'])) { 
        $cho = cleanInput($_POST['checkoutdate']);
        $checkoutdate = (strlen($cho)>50)?substr($cho,1,50):$cho;
      }
      else {
        $error++; //bump the error flag
        $msg .= 'Invalid CheckOut Date '; //append eror message
        $checkoutdate = '';  
      }        
//contactnumber
      if (isset($_POST['contactnumber']) and !empty($_POST['contactnumber']) and is_string($_POST['contactnumber'])) {
        $fn = cleanInput($_POST['contactnumber']); 
        $contactnumber = (strlen($fn)>30)?substr($fn,1,30):$fn; //check length and clip if too big
        //we would also do context checking here for contents, etc       
      } else {
        $error++; //bump the error flag
        $msg .= 'Invalid phone number '; //append eror message
        $contactnumber = '';  
      }                  
//extras    
      if (is_string($_POST['bookingextras'])) {
        $fn = cleanInput($_POST['bookingextras']); 
        $bookingextras = (strlen($fn)>900)?substr($fn,1,900):$fn; //check length and clip if too big
        //we would also do context checking here for contents, etc       
      } else {
        $error++; //bump the error flag
        $msg .= 'Invalid bookingextas '; //append eror message
        $bookingextras = '';  
      }   
//roomreview
      if (is_string($_POST['roomreview'])) {
        $fn = cleanInput($_POST['roomreview']); 
        $roomreview = (strlen($fn)>1500)?substr($fn,1,900):$fn; //check length and clip if too big
        //we would also do context checking here for contents, etc       
      } else {
        $error++; //bump the error flag
        $msg .= 'Invalid bookreview '; //append eror message
        $roomreview = '';  
      }              
    
//save the room data if the error flag is still clear and booking id is > 0
    if ($error == 0 and $id > 0)
	  {
        $query = "UPDATE booking SET checkindate=?,checkoutdate=?,contactnumber=?,bookingextras=?,roomreview=? WHERE bookingID=?";
        $stmt = mysqli_prepare($db_connection, $query); //prepare the query
        mysqli_stmt_bind_param($stmt,'sssssi', $checkindate,$checkoutdate,$contactnumber,$bookingextras,$roomreview, $id); 
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);    
        echo "<h2>Booking details updated.</h2>";  
    } 
	  else
	  { 
      echo "<h2>$msg</h2>";
    }      
}

//locate the booking to edit by using the bookingID
//we also include the booking ID in our form for sending it back for saving the data
$query = 'SELECT bookingID,roomname,roomtype,beds,checkindate,checkoutdate,contactnumber,bookingextras,roomreview FROM booking
INNER JOIN room
ON booking.roomID = room.roomID
WHERE bookingID='.$id;
$result = mysqli_query($db_connection,$query);
$rowcount = mysqli_num_rows($result);
if ($rowcount > 0) {
  $row = mysqli_fetch_assoc($result);

?>
<h1>Booking Details Update</h1>
<h2><a href='listbookings.php'>[Return to the list of bookings]</a><a href='index.php'>[Return to the main page]</a></h2>

<form method="POST" action="editbooking.php">
  <input type="hidden" name="id" value="<?php echo $id;?>">
   <p>
   <label for="pickroom">Room(name, type, beds): </label>
    <select id="pickroom" name="pickroom" minlength="5" maxlength="50" required> 
      <option><?php echo $row['roomname'].", ".$row['roomtype'].", ".$row['beds']; ?></option>
    </select>
   </p> 
  <p>
    <label for="checkindate">Check in: </label>
    <input type="date" id="checkindate" name="checkindate" value="<?php echo $row['checkindate']; ?>" required> 
  </p>
  <p>
    <label for="checkoutdate">Checkout date: </label>
    <input type="date" id="checkoutdate" name="checkoutdate" value="<?php echo $row['checkoutdate']; ?>" required> 
  </p>    
  <p>  
    <label for="contactnumber">Contact number: </label>
    <input type="number" id="contactnumber" name="contactnumber" placeholder="(xxx)xxxxxxx" minlength="7" maxlength="20" value="<?php echo $row['contactnumber']; ?>" required>
   </p>
  <p>
    <label for="bookingextras">Booking Extras: </label>
    <textarea name="bookingextras" id="bookingextras" cols="40" rows="5" <?php echo '<textarea name ="bookingextras" id="bookingextras">'.$row['bookingextras'].'</textarea>'; ?> 
  </p> <!-- cant understand why it is red-->
  <p>
    <label for="roomreview">Room review:</label>
    <textarea type = "text" name="roomreview" id="roomreview" cols="40" rows="5" <?php echo '<textarea name ="roomreview" id="roomreview">'.$row['roomreview'].'</textarea>'; ?>
  </p> 
   <input type="submit" name="submit" value="Update">
   <a href="listbookings.php">[Cancel]</a>
 </form>
<?php 
} 
else
{ 
  echo "<h2>booking not found with that ID</h2>"; //simple error feedback
}
mysqli_close($db_connection); //close the connection once done
?>
</body>
</html>
  