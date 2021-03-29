<?php
    // Start the session
    session_start();

    //Redirect user if they are not logged in and are not an admin.
    if(!isset($_SESSION['ID']) and $_SESSION["admin"] != 1)
    { 
        header("Location: index.html");
        exit;
    }

?>

<!DOCTYPE html>
<html>
    
<head>
    <meta name="viewport" content="initial-scale=1.0; maximum-scale=1.0; width=device-width;">
    <link rel="stylesheet" href="style.css"> <!--Links the CSS file.-->
</head>
    
<body>

<script>
    //If search field is empty, value will be changed to something that will return no results from DB.
    function checkEmpty()
    {
        if (document.getElementById('adminSearch').value == '')
            {
                document.getElementById('adminSearch').value = '                                ';
            }
    }
</script>
    
    <!--Navigation Bar-->
    <nav>
        <ul>
            <li>
                <a class="active" href="adminMonitoring.php">Admin Monitoring</a>
            </li>
            <li>
                <a href="logout.php">Logout</a>
            </li>
        </ul>
    </nav>
    
    <h1>Admin Monitoring</h1>
    
    <br>
    
    <!--Form-->
        <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> <!--Send form data to self via get (avoids form resubmission messages on page reload)-->
            
            <label for="adminSearch">Search for Vynils/Accounts:</label><br><br>
            
            <div>
                <input type="text" placeholder="email/title/artist" name="adminSearch" id="adminSearch" style="height: 30px"><br><br>
            
                <button type="submit" onclick="checkEmpty();">Search</button> <!--Calls the checkEmpty() JS function so that if search field is empty, there will be no matches in DB.-->
                <button type="submit" onclick="document.getElementById('adminSearch').value = ''">Show All</button> <!--Sets the search field to '' so that DB will return everything.-->
            </div>
            
        </form>
    
<?php
    require('databaseConnect.php'); //Provides access to databaseConnect.php during script execution.

    //Variables used.
    $search; //Variable stores the target search string.
    $query; //Variable stores query for the database
    $result; //Variable stored data returned from database.

    if(isset($_GET["adminSearch"])) //isset check avoids $search being null and then automatically loading everything in the Table when page is first loaded.
    { 

        $search = "%" . $_GET["adminSearch"] . "%";

        $search = mysqli_real_escape_string($dbConnect, $search); //Prevents SQL injection attacks.

        $query = "SELECT UserID, Email FROM UserTable WHERE Email LIKE '$search'"; //Case insensitive query that will check if Email column of UserTable contains target 

        $result = @mysqli_query($dbConnect, $query); //Connect to database, send query and store result.

        if($result and ($result -> num_rows > 0)) //If the query runs successfully and returns data then create Email table headers.
        {
?>      
        <br>
        <br>
        <table class="table-fill">
        <thead>
        <tr>
            <th class="text-left">Email</th>
        </tr>
        </thead>
        <tbody class="table-hover">
<?php
            //Loop through DB row by row.
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
            {
?>

            <tr>
                <td class="text-left"><?php echo '<a href="editAccount.php?userID='. $row['UserID'] .'" >'. $row['Email']; ?></td> <!--Populate table with matching Emails-->
            </tr>

<?php

            }
?>
        </tbody>
        </table>
        <br>
        <br>
        <br>
        <br>
<?php

        }
        else 
        {
            echo '<div class="my-padding-64"><p>No matching accounts.</p></div>'; //Otherwise display no matching Emails message

        }

        $query = "SELECT ProductID, Title, Artist FROM MusicTable WHERE Title LIKE '$search' or Artist LIKE '$search'"; //Case insensitive query that will check if Title or Artist columns of MusicTable contain target.

        $result = @mysqli_query($dbConnect, $query); //Connect to database, send query and store result.

        if($result and ($result -> num_rows > 0)) //If the query runs successfully and returns data then create Vynil table headers.
        {
?>
        <br>
        <br>
        <table class="table-fill">
        <thead>
        <tr>
            <th class="text-left">Title</th>
            <th class="text-left">Artist</th>
        </tr>
        </thead>
        <tbody class="table-hover">
<?php
            //Loop through DB row by row.
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
            {
?>

            <tr>
                <td class="text-left"><?php echo '<a href="editItem.php?productID='. $row['ProductID'] .'" >'. $row['Title']; ?></td> <!--Populate table with matching Titles-->
                <td class="text-left"><?php echo '<a href="editItem.php?productID='. $row['ProductID'] .'" >'. $row['Artist']; ?></td> <!--Populate table with matching Artists-->
            </tr>

<?php
            }
?>
        </tbody>
        </table>
        <br>
        <br>
        <br>
        <br>
<?php

        }
        else 
        {
            echo '<div class="my-padding-64"><p>No matching vynils.</p></div>'; //Otherwise display no matching vynils message.

        }
            
        mysqli_free_result($result); //Empties out DB results, freeing RAM.        

        mysqli_close($dbConnect); //Close connection.

        
    }
    
    include 'footer.html'; //Display footer.
?>
    
</body>
</html>
