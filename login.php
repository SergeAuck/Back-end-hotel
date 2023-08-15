<?php 
 session_start(); 
?> 
 <?php 
     include 'checksession.php'; 
     loginStatus(); 
 ?>
<!DOCTYPE html> 
 <html> 
   <head> 
     <title>Login</title> 
     <meta charset="UTF-8"> 
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> 
   </head> 
   <body>
        <div class="container"> 
            <h1>Welcome to the Login page</h1> 
        </div></br>
        <div class="container"> 
             <li><a href="index.php">Main Menu</a> 
             <form method="POST" action="login.php"> 
                <h1>Customer Login</h1> 
                <label for="email">Email: </label> 
                <input type="email" id="email" size="30"  name="email" required>  
                <p> 
                <label for="password">Password: </label> 
                <input type="password" id="password"  name="password" min="8" max="30" required>  
                </p> 
                <input type = "submit" name="submit" value="Login"> 
             </form>
        </div> </br>
        <div class="container">
            <h3>If you want to log out</h3> 
            <p>Press <a href="logout.php">here</a> to log out</p> 
        </div>
     <?php
        include 'config.php'; //load in any variables
        include "cleaninput.php";
        $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
        if (mysqli_connect_errno())
        {
            echo  "Error: Unable to connect to MySQL. ".mysqli_connect_error();
            exit;
        }
        // if the login form has been filled in 
        if (isset($_POST['email'])) 
        { 
            $email = $_POST['email']; 
            $password = $_POST['password']; 
 
            //prepare a query and send it to the server 
            $stmt = mysqli_stmt_init($db_connection); 
            mysqli_stmt_prepare($stmt, "SELECT customerID,lastname, password,role FROM customer WHERE email=?"); 
            mysqli_stmt_bind_param($stmt, "s", $email); 
            mysqli_stmt_execute($stmt); 
            mysqli_stmt_bind_result($stmt, $customerID, $lastname, $hashpassword, $role); 
            mysqli_stmt_fetch($stmt); 
            // this is where the password is checked 
            
            if(!$customerID) 
            { 
                echo '<p class="error">Unable to find member with email!'.$email.'</p>'; 
            } 
                else 
            { 
                if (password_verify($password, $hashpassword)) 
                { 
                    $_SESSION['loggedin'] = true;
                    $_SESSION['email'] = $email; 
                    $_SESSION['role'] = $role;
                    $_SESSION['customerID'] = $customerID;
                    $_SESSION['username'] = $lastname;
                    header('Location: http://localhost/motuekaAS2/index.php');
                    echo '<p>Congratulations, you are logged in!</p>'; 
                    echo '<p>Please return to this page later on to log out</p>';
                    echo $role;
                } 
                    else 
                { 
                    echo '<p>Username/password combination is wrong!</p>'; 
                } 
            } 
            mysqli_stmt_close($stmt);
        }
        mysqli_close($db_connection); //close the connection once done
        ?>
        
   </body> 
 </html> 