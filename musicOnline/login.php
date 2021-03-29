<!-- login.php -->
<?php
    // Start the session
    session_start();
?>

<!DOCTYPE HTML>  
<html>
    <head>
    </head>
    
    <body>  
        <?php
            require('databaseConnect.php'); //Provides access to databaseConnect.php during script execution.
        
            //Variables used.
            $email; //Variable stores submitted email.
            $pass; //Variable stores submitted password.
            $query; //Variable stores query for the database
            $result; //Variable stored data returned from database.
            

            $email = $_POST["email"]; //Acquire email from user. No need for SQL injection protection as this does not interact with the database.
            $pass = $_POST["password"]; //Acquire password from user. No need for SQL injection protection as this does not interact with the database.
        
            //Check if user is an Admin.
            $query = "SELECT UserID, Email, PassHash FROM AdminTable"; //Request UserID, Email and PassHash from the Admin Table of DB.
            $result = @mysqli_query($dbConnect, $query); //Connect to database, send query and store result.
        
            if($result) //If the query runs successfully
            {
                //Loop through DB row by row.
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
                {
                    if ($email == $row['Email']) //Check if email matches.
                    {
                        if (password_verify($pass, $row['PassHash'])) //Check if passHash in DB matches hash of entered password.
                        {
                            // Success! Set session variables to track user and go to adminMonitoring.php.
                            $_SESSION["ID"] = $row['UserID'];
                            $_SESSION["admin"] = 1;
                            header('Location: adminMonitoring.php');
                            exit;
                        }
                    }
                }
                
                //If couldn't find target in AdminTable then search UserTable.
                $query = "SELECT UserID, Email, PassHash FROM UserTable"; //Request UserID, Email and PassHash from the Admin Table of DB.
                $result = @mysqli_query($dbConnect, $query); //Connect to database, send query and store result.
                
                //Loop through DB row by row.
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
                {
                    if ($email == $row['Email']) //Check if email matches.
                    {
                        if (password_verify($pass, $row['PassHash'])) //Check if passHash in DB matches hash of entered password.
                        {
                            // Success! Set session variables to track user and go to searchRecords.php.
                            $_SESSION["ID"] = $row['UserID'];
                            $_SESSION["userEmail"] = $row['Email'];
                            $_SESSION["admin"] = 0;
                            header('Location: searchRecords.php');
                            exit;
                        }
                    }
                }
            
            //Failed login.    
            setcookie("error", "Error: Username or password is incorrect.", time() + 3, "/"); //Create an error message cookie. (cookie name, cookie value, 3 second expiration time, directory that it is available in on the website).
            header('Location: index.html'); //Go to index.html
                
                mysqli_free_result($result); //good housekeeping - cleans up after large queries and also signifies we're finished with the dataset
            } 
            else 
            {
                echo '<p>error</p>'; //custom error - this will show for syntax error rather than a php server error message
                echo '<p>' . mysqli_error($dbConnect) . '</p>';
            }

        mysqli_close($dbConnect);

        ?>
    </body>
</html>