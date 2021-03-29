<?php
    // Start the session
    session_start();

    //Redirect user if they are not logged in.
    if(!isset($_SESSION['ID']))
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
    <!--Navigation Bar-->      
    <nav>
        <ul>
            <li>
                <a href="searchRecords.php">Search Records</a>
            </li>
            <li>
                <a class="active" href="mySales.php">My Sales</a>
            </li>
            <li>
                <a href="editAccount.php">My Account</a>
            </li>
            <li>
                <a href="logout.php">Logout</a>
            </li>
          </ul>
        </nav>
        
    <h1>My Sales</h1>

<?php
    require('databaseConnect.php'); //Provides access to databaseConnect.php during script execution.

    //Variables used.
    $query; //Variable stores query for the database
    $result; //Variable stored data returned from database.
    
    /*-----------CREATE NEW ITEM---------------*/
    if ($_SERVER['REQUEST_METHOD'] == "GET" and isset($_GET['newItemButton']))
    {
        
        //Create a new item with placeholder values
        $query = "INSERT INTO MusicTable (SellerID, ImagePath, Title, Price, Artist, Publisher, ReleaseDate, RunningTime, Description) VALUES ('{$_SESSION['ID']}', 'placeholder.jpg', 'Placeholder Name', '0.00', 'Placeholder Artist', 'Placeholder Publisher', '2020-01-01', '0', 'Placeholder Description.')";
        mysqli_query($dbConnect, $query); //Connect to database, send query and store result.

        if(mysqli_affected_rows($dbConnect) > 0) //Check that a row was changed.
        {
            mysqli_free_result($result); //Empties out DB results, freeing RAM.
            header('Location: mySales.php'); //Reload the page to display new placeholder item.
            exit; //Prevent further code execution.

        }
        else 
        {
            echo '<p>error</p>'; //Custom error - just in case. Should never be triggered.
            echo '<p>' . mysqli_error($dbConnect) . '</p>';
        }

    }
    
    
    /*-----------DISPLAYING TABLE---------------*/
    $query = "SELECT ProductID, Title, Artist FROM MusicTable WHERE sellerID = '{$_SESSION['ID']}'"; //Case insensitive query that will check if columns contains target.
    $result = @mysqli_query($dbConnect, $query); //Connect to database, send query and store result.

    if($result and ($result -> num_rows > 0)) //If the query runs successfully and returns data, create Vynil Table headers.
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
        
        //Loop through DB row by row. Populate table with results.
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
        {
?>

            <tr>
                <td class="text-left"><?php echo '<a href="editItem.php?productID='. $row['ProductID'] .'" >'. $row['Title']; ?></td>
                <td class="text-left"><?php echo '<a href="editItem.php?productID='. $row['ProductID'] .'" >'. $row['Artist']; ?></td>
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

    mysqli_free_result($result); //Empties out DB results, freeing RAM.

    } 
    else 
    {
        echo '<p>No items currently on sale.</p>'; 

    }

    mysqli_close($dbConnect); //Close connection.

?>
    
            <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> <!-- Reload page and send a get request to self-->
            <div class="my-padding-64">
                <button type="submit" name="newItemButton" >Create New Item</button>
            </div>
        </form>
    
    <br>
    <br>
    <br>
    <br>
    
<?php
    include 'footer.html'; //Display footer.
?>
        
    </body>
    
</html>