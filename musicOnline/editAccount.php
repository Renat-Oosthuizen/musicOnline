<?php
    // Start the session
    session_start();

    $targetUserID; //Variable stores UserID of the account being edited.

    //Redirect user if they are not logged in and are not an admin.
    if(!isset($_SESSION['ID']) and $_SESSION["admin"] != 1)
    { 
        header("Location: index.html");
        exit;
    }
    
    if(isset($_GET['userID'])) //If coming from adminMonitoring.
    {
        $targetUserID = $_GET['userID'];
        $_SESSION["targetUserID"] = $targetUserID;
    }
    else if(isset($_SESSION["targetUserID"])) //If reloading the page as an admin.
    {
        $targetUserID = $_SESSION["targetUserID"];
    }
    else //If coming from a non-admin user.
    {
        $targetUserID = $_SESSION['ID'];
    }
?>

<!DOCTYPE html>

<html>
    
<head>
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, width=device-width">
    <link rel="stylesheet" href="style.css"> <!--Links the CSS file.-->
</head>
    
<body>
    
<?php
    
       //Dynamic navbar that changes based on if the user is an admin.
       if ($_SESSION["admin"] == 1)
       {
            echo "<nav>";
                echo "<ul>";
                    echo "<li>";
                        echo "<a href=\"adminMonitoring.php\">Admin Monitoring</a>";
                    echo "</li>";
                    echo "<li>";
                        echo "<a href=\"logout.php\">Logout</a>";      
                    echo "</li>";
                echo "</ul>";
            echo "</nav>";
       }
       else
       {
            echo "<nav>";
                echo "<ul>";
                    echo "<li>";
                        echo "<a href=\"searchRecords.php\">Search Records</a>";
                    echo "</li>";
                    echo "<li>";
                        echo "<a href=\"mySales.php\">My Sales</a>";   
                    echo "</li>";
                    echo "<li>";
                        echo "<a class=\"active\" href=\"editAccount.php\">My Account</a>";
                    echo "</li>";
                    echo "<li>";
                        echo "<a href=\"logout.php\">Logout</a>";    
                    echo "</li>";
                echo "</ul>";
            echo "</nav>";
       }

?>
    
    <h1>Edit Account</h1>
        
<script>

    function verifyPass() //Javascript function used to verify that passwords match
    {
        var pass1 = document.getElementById('newPassword').value; //Stores initial password
        var pass2 = document.getElementById('repeatNewPassword').value; //Stores repeat password

        if (pass1 == pass2)
            {
                return true;
            }
        else
            {
                alert("Passwords do not match!");
                return false;
            }
    }
    
    //Javascript function that asks user for confirmation of account deletion.
    function confirmDelete()
    {
        var confirmation = confirm("Are you sure you want to permanently delete this Account? All associated vynils currently being sold will also be deleted.");
        
        if (confirmation == true)
        {
            return true; //Triggers deletion
        }
        else
        {
            return false;
        }
    }

</script>
    
<?php
    
    require('databaseConnect.php'); //Provides access to databaseConnect.php during script execution.

    //Variables used.
    $query; //Variable stores query for the database
    $result; //Variable stored data returned from database.
    
        /*--------FUNCTIONS--------*/
    
    //Changing general account details.
    if(isset($_POST['newEmail'])) 
    {
        $email = mysqli_real_escape_string($dbConnect, $_POST['newEmail']);
        $phone = mysqli_real_escape_string($dbConnect, $_POST['newPhone']);
        
        if ($email == "adminguy@gmail.com" or $email == "theboss@gmail.com") //If new email matches admin then error.
        {
            echo "<p><b>This email is already in use. Account details have not been changed!</b><p>";
        }
        else
        {
            $query = "UPDATE UserTable SET Email = '$email', Phone = '$phone' WHERE UserID = '$targetUserID'"; //Query to edit the Email row.

            mysqli_query($dbConnect, $query); //Connect to database, send query and store result.

            if(mysqli_affected_rows($dbConnect) > 0) //Check that a row was changed.
            {
                echo "<p><b>Account details changed!</b><p>";              
            } 
            else 
            {
                echo "<p><b>No new data or Email is already in use. Account details have not been changed!</b><p>"; 
            }
        }
    }


    //Changing password.
    if(isset($_POST['newPassword'])) 
    {
        
        $pass = mysqli_real_escape_string($dbConnect, $_POST['newPassword']);

        $pass = password_hash($pass, PASSWORD_BCRYPT); //Create a salted hashed password using BCrypt hashing function.

        $query = "UPDATE UserTable SET PassHash = '$pass' WHERE UserID = '$targetUserID'"; //Query to edit the PassHash column.

        mysqli_query($dbConnect, $query); //Connect to database, send query and store result.

        if(mysqli_affected_rows($dbConnect) > 0) //Check that a row was changed.
        {
            echo "<p><b>Password changed successfully!</b><p>";                
        } 
        else 
        {
            echo '<p>Error, see below for details...</p>'; //Custom error - just in case. Should never be triggered.
            echo '<p>' . mysqli_error($dbConnect) . '</p>';
        }

    }

    //Should call deleteAccount() when the delete button is pressed.
    if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['deleteButton']))
    {

        $query = "DELETE FROM MusicTable WHERE SellerID = '$targetUserID'"; //Delete all lines where the SellerID = target UserID

        mysqli_query($dbConnect, $query); //Connect to database and send query.

        $query = "DELETE FROM UserTable WHERE UserID = '$targetUserID'"; //Delete line with the target UserID.

        mysqli_query($dbConnect, $query); //Connect to database and send query.

        if(mysqli_affected_rows($dbConnect) > 0) //Check that a row was changed.
        {
            if($_SESSION["admin"] != 1) //If user is not an Admin, then they have deleted their account and must logout.
            {
                header('Location: logout.php'); //Logout user.
                exit; //Prevent further code execution.
            }
            else
            {
                header('Location: adminMonitoring.php'); //Send user back to adminMonitoring.
                exit; //Prevent further code execution.   
            }

        } 
        else 
        {
            echo '<p>Error, see below for details...</p>'; //Custom error - just in case. Should never be triggered.
            echo '<p>' . mysqli_error($dbConnect) . '</p>';
        }

        mysqli_free_result($result); //Empties out DB results, freeing RAM.        


    }

        /*-------------MAIN CODE----------------------*/

        $query = "SELECT Retailer, Email, Phone FROM UserTable WHERE UserID = '$targetUserID'"; //Case insensitive query that will check if columns contains target.
        $result = @mysqli_query($dbConnect, $query); //Connect to database, send query and store result.

        if($result) //If the query runs successfully
        {
            //Loop through DB row by row.
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
            {
?>
            <br>
            <br>
            <div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post"> <!-- Change Account Details form, posts to self-->
                     <fieldset>
                        <br>
                        <legend>Change Account Details:</legend>
                        <label for="email">New Email:</label><br> <!-- "for" links to input id-->
                        <input type="email" value="<?php echo $row['Email'];?>" name="newEmail" id="newEmail" size="30" required><br><br>
                        <label for="phoneNumber">New Phone Number:</label><br> <!-- "for" links to input id-->
                        <input type="tel" value="<?php echo $row['Phone'];?>" name="newPhone" id="newPhone" size="30" required><br><br>

                        <button type="submit">Submit</button><br><br>

                    </fieldset>
                </form>
            </div>

            <br>
            <br>
            <br>

            <div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" onsubmit="return verifyPass();" method="post"> <!-- Reset Password form, posts to self if verifyPass() returns true-->
                    <fieldset>
                        <br>
                        <legend>Reset Password:</legend>
                        <label for="password">New Password:</label><br> <!-- "for" links to input id-->
                        <input type="password" placeholder="Enter New Password" name="newPassword" id="newPassword" size="30" required><br><br>
                        <label for="repeatPassword">Repeat Password:</label><br> <!-- "for" links to input id-->
                        <input type="password" placeholder="Enter New Password" name="repeatNewPassword" id="repeatNewPassword" size="30" required><br><br>

                        <button type="submit">Submit</button><br><br>

                    </fieldset>
                </form>
            </div>


<?php     
            }

            mysqli_free_result($result); //Empties out DB results, freeing RAM.   

        } 
        else 
        {
            echo '<p>Error, see below for details...</p>'; //Custom error - just in case. Should never be triggered.
            echo '<p>' . mysqli_error($dbConnect) . '</p>';
        }

        mysqli_close($dbConnect); //Close connection.

?> <!-- Delete Account Button-->
    <div>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" onsubmit="return confirmDelete();">
            <div class="my-padding-64">
                <button type="submit" name="deleteButton" style="color: white; background-color: red; border-style: solid">Delete Account</button>
            </div>
        </form>
    </div>
    <br>
    <br>
    
<?php
    
    include 'footer.html'; //Display footer.
    
?>
    </body>
    
</html>