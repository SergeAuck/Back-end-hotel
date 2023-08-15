<?php 
session_start(); 
include 'checksession.php'; 
checkUser(); 
?>
<?php
//Our customer search/filtering engine
include "config.php"; //load in any variables
$db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE) or die();


$fromdate = $_POST["from_date"];
$todate = $_POST["to_date"];
//do some simple validation to check if sq contains a string
//$sq = $_GET['sq'];
//$searchresult = '';
if (isset($_POST["from_date"], $_POST["to_date"])) {
    
    $orderData = "";
    $query = "SELECT * FROM room WHERE roomID NOT IN (SELECT roomID FROM booking WHERE checkindate >= '$fromdate' AND checkoutdate <= '$todate')";
    $result = mysqli_query($db_connection, $query);

    $orderData .='
    <table class="table table-bordered">
    <tr>
    <th width="25%">Room #</th>
    <th width="25%">Room name</th>
    <th width="25%">Room type</th>
    <th width="25%">beds</th>
    </tr>';
 
    if(mysqli_num_rows($result) > 0)
    {
        while($row = mysqli_fetch_array($result))
        {
            $orderData .='
            <tr>
            <td>'.$row["roomID"].'</td>
            <td>'.$row["roomname"].'</td>
            <td>'.$row["roomtype"].'</td>
            <td>'.$row["beds"].'</td>
            </tr>';
        }
    }
    else
    {
        $orderData .= '
        <tr>
        <td colspan="5">No room Found</td>
        </tr>';
    }
    $orderData .= '</table>';
    echo $orderData;
}
?>