<?php 
 session_start(); 
?> 
 <?php 
   include "checksession.php";
   loginStatus(); 
 ?>
<!DOCTYPE html> 
 <html lang="en"> 
   <head> 
     <title>Log Out</title> 
     <meta charset="UTF-8"> 
     <meta name="viewport" content="width=device-width, initial-scale=1"> 
   </head> 
   <body> 
     <h1>LOGOUT</h1> 
     <?php 
        
        logout();
        header('Location: http://localhost/motuekaAS2/index.php') 
     ?> 
     <p><a href="index.php">You are now logged out. Return to the menu</a></p> 
   </body> 
 </html> 