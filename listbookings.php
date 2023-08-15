<?php
session_start(); 
include "checksession.php";
checkUser();
loginStatus(); 
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>Current Bookings</title>
    </head>
<body>

<?php
include "config.php"; //load in any variables
$db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

//insert DB code from here onwards
//check if the connection was good
if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
    exit; //stop processing the page further
}

//prepare a query and send it to the server
$query = 'SELECT bookingID,roomname,checkindate,checkoutdate,firstname,lastname FROM booking
INNER JOIN room
ON booking.roomID = room.roomID
INNER JOIN customer
ON booking.customerID = customer.customerID
ORDER BY checkindate';
$result = mysqli_query($db_connection, $query);
$rowcount = mysqli_num_rows($result); 
?>
<div class="container">
    <h1>Current Bookings</h1>
</div>
<div class="container">
    <h2><a href='addbooking.php'>[Add a booking]</a><a href="index.php">[Return to main page]</a></h2>
</div>

<table border="1">
<thead><tr><th>Booking (room, dates)</th><th>Customer</th><th>Action</th></tr></thead>
<?php
//makes sure we have rooms
if ($rowcount > 0) {  
    while ($row = mysqli_fetch_assoc($result)) {
	  $id = $row['bookingID'];	
	  echo '<tr><td>'.$row['roomname'].",  ".$row['checkindate'].", ".$row['checkoutdate'].'</td><td>'.$row['firstname']."  ".$row['lastname'].'</td>';
	  echo '<td><a href="viewbooking.php?id='.$id.'">[view]</a>';
	  echo '<a href="editbooking.php?id='.$id.'">[edit]</a>';
	  echo '<a href="deletebooking.php?id='.$id.'">[delete]</a></td>';
      echo '</tr>';
   }
} else echo "<h2>No bookings found!</h2>"; //suitable feedback

mysqli_free_result($result); //free any memory used by the query
mysqli_close($db_connection); //close the connection once done
?>
</table>

</body>
</html>