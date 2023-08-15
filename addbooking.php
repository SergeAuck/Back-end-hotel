<?php 
session_start(); 
include 'checksession.php';
//checkUser();  
loginStatus(); 

 if(!checkUser()) 
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
<html>
<head><title>Add a new booking</title>
<title>Make new booking</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- Scaling not important ATM? -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel='stylesheet' type='text/css' href='./Styles/default.css'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script
      src="https://code.jquery.com/jquery-3.6.1.js"
      integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
      crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        $(function() {
          $("#checkindate").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd'
          });

          $("#checkoutdate").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd'
          });

          $("#from_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd'
          });

          $("#to_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd'
            });
        });

        $(document).ready(function() {  
        });  
    </script>
    
</head>

<?php
include "config.php"; //load in any variables
include "cleaninput.php";
$query = "SELECT * FROM room ORDER BY roomID desc";
$db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
$result = mysqli_query($db_connection, $query);
//the data was sent using a formtherefore we use the $_POST instead of $_GET
//check if we are saving data first by checking if the submit button exists in the array
if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Add')) {
//if ($_SERVER["REQUEST_METHOD"] == "POST") { //alternative simpler POST test    
    //include "config.php"; //load in any variables
    //$db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

    if (mysqli_connect_errno()) {
        echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
        exit; //stop processing the page further
    };


    
//validate incoming data - only the first field is done for you in this example - rest is up to you do
        $error = 0; //clear our error flag
        $msg = 'Error: ';
//customerID probably no need to check cos no incoming data from user?
        $customerID = cleanInput($_SESSION['customerID']); 
//roomID user can pick only one of available options
        $roomID = cleanInput($_POST['roomID']);        
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
       
//save the booking data if the error flag is still clear
        if ($error == 0) {
            $query = "INSERT INTO booking (customerID,roomID,checkindate,checkoutdate,contactnumber,bookingextras) VALUES (?,?,?,?,?,?)";
            $stmt = mysqli_prepare($db_connection, $query); //prepare the query
            mysqli_stmt_bind_param($stmt,'iissss', $customerID, $roomID, $checkindate, $checkoutdate, $contactnumber, $bookingextras); 
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);    
            echo "<h2>New booking added to the list</h2>";        
        } else { 
        echo "<h2>$msg</h2>".PHP_EOL;
        }     

}
  
?>  
<body>  
<div class="container">               
    <h1>Add a new booking</h1>
    <h2><a href='listbookings.php'>[Return to the list of bookings]</a><a href='index.php'>[Return to the main page]</a></h2>
</div>
<div class="container">
<form method="POST" action="addbooking.php">
    <p>Room(name, type, beds):
        <?php
        $db = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
        $query = $db->query("SELECT roomID, roomname, roomtype, beds FROM room"); 
        echo '<select name="roomID" id="roomID">'; 
        while ($row = $query->fetch_assoc()) {
            echo '<option value="'.$row['roomID'].'">'.$row['roomname'].'-'.$row['roomtype'].'-'.$row['beds'].'</option>';
        }
        echo '</select>';
        ?>
    </p> 
</div>
        <p>
        <div class="container">
            <label for="checkindate">Checkin date: </label>
            <input type="text" id="checkindate"  name="checkindate" required> 
        </p>  
        <p>
            <label for="checkoutdate">Checkout date:</label>
            <input type="text" id="checkoutdate" name="checkoutdate" required>
        </p>
        <p>
            <label for="mobile">Contact number:</label>
            <input type="number" id="contactnumber" name="contactnumber" placeholder="(xxx)xxxxxxx" minlength="7" maxlength="20" required>
        </p> 
        <p>
            <label for="bookingextras">Booking extras:</label>
            <textarea name="bookingextras" id="bookingextras" cols="40" rows="5"></textarea>
        </p>     
        <input type="submit" name="submit" value="Add">
        </div>
        <p>
<div class="container">
    <h2>Room availability search</h2></br>
    <div class="row">
        <div class="col-md-2">
            <input type="text" name ="from_date" id="from_date" class="form-control dateFilter" placeholder="Checkin Date"/>
        </div>
        <div class="col-md-2">
            <input type="text" name ="to_date" id="to_date" class="form-control dateFilter" placeholder="Checkout Date"/>
        </div>
        <div class="col-md-2">
            <input type="button" name ="search" id="btn_search" class="btn btn-primary" value="Search"/>
        </div>
    </div></br>
    <div class="row">
        <div class="col-md-8">
            <div id="purchase_order">
                <table class="table table-bordered">
                        <tr>
                        <th width="25%">Room #</th>
                        <th width="25%">Room name</th>
                        <th width="25%">Room type</th>
                        <th width="25%">Beds/th>
                        </tr>
                        <?php
                        while($row = mysqli_fetch_array($result))
                        {
                        ?>
                        <tr>
                            <td><?php echo $row["roomID"]; ?></td>
                            <td><?php echo $row["roomname"]; ?></td>
                            <td><?php echo $row["roomtype"]; ?></td>
                            <td><?php echo $row["beds"]; ?></td>
                        </tr>
                        <?php
                        }
                        ?>
                </table>
            </div>
        </div>
    </div>

</div>
</p>
 </form>
 <script>
        $(document).ready(function () {
        
            $('.dateFilter').datepicker({
            dateFormat: "yy-mm-dd"
            });
        
            $('#btn_search').click(function () {
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            if (from_date != '' && to_date != '') {
                $.ajax({
                url: "roomsearch.php",
                method: "POST",
                data: { from_date: from_date, to_date: to_date },
                success: function (data) {
                    $('#purchase_order').html(data);
                }
                });
            }
            else {
                alert("Please Select the Date");
            }
            });
        });
    </script>
</body>
</html>
  