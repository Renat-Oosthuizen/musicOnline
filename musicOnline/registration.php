<!DOCTYPE HTML>  
<html>
    <head>
    </head>
    
    <body>  
        <?php
        require('databaseConnect.php'); //Provides access to databaseConnect.php during script execution.
        
        //Variables used.
        $retailer; //Variable stores if the account is a retailer type or not.
        $email; //Variable stores submitted email.
        $phone; //Variable stores submitted phone number
        $pass; //Variable stores submitted password.
        $query; //Variable stores query for the database
        $result; //Variable stored data returned from database.
            
        $retailer = mysqli_real_escape_string($dbConnect, $_POST["account"]); //Acquire account type from user.
        $email = mysqli_real_escape_string($dbConnect, $_POST["email"]); //Acquire email from user.
        $phone = mysqli_real_escape_string($dbConnect, $_POST["phoneNumber"]); //Acquire phone number from user.
        $pass = mysqli_real_escape_string($dbConnect, $_POST["password"]); //Acquire password from user.
        
        $query = "SELECT Email FROM UserTable WHERE Email = '$email'"; //Query will search UserTable for an identical email.
        
        $result = @mysqli_query($dbConnect, $query); //Connect to database, send query and store result.
        
        if ($email == "adminguy@gmail.com" or $email == "theboss@gmail.com" or $row = mysqli_fetch_array($result, MYSQLI_ASSOC)) //If a match is found then echo an error. No point searching AdminTable for 2 emails.
        {
            setcookie("error", "Error: This email is already in use.", time() + 3, "/"); //Error message cookie. (cookie name, cookie value, 3 second expiration time, directory that it is available in on the website).
            header('Location: registration.html');
        }
        else
        {
        
        $pass = password_hash($pass, PASSWORD_BCRYPT); //Create a salted hashed password using BCrypt hashing function.
        
        $query = "INSERT INTO UserTable(Retailer, Email, Phone, PassHash) VALUES ('$retailer', '$email', '$phone', '$pass')"; //Query to add a new account.
        
        @mysqli_query($dbConnect, $query); //Connect to database and send query.
        
        setcookie("newAccount", "true", time() + 3, "/"); //Cookie used to notify user that an account has been created.
            
        header('Location: index.html'); //Go to login page.
            
        }

        ?>
    </body>
</html>