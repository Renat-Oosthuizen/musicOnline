<?php
    // Start the session
    session_start();

    //Redirect user if they are not logged in.
    if(!isset($_SESSION['ID']))
    { 
        header("Location: index.html");
        exit;
    }

    $_SESSION['productID'] = $_GET['productID']; //Get productID from searchRecords.php
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
                <a href="mySales.php">My Sales</a>
            </li>
            <li>
                <a href="editAccount.php">My Account</a>
            </li>
            <li>
                <a href="logout.php">Logout</a>
            </li>
        </ul>
    </nav>
        
    <h1>Item</h1>
    
<?php
        require('databaseConnect.php'); //Provides access to databaseConnect.php during script execution.

        //Variables used.
        $query; //Variable stores query for the database
        $result; //Variable stored data returned from database.

        $query = "SELECT * FROM MusicTable WHERE ProductID = '{$_SESSION['productID']}'"; //Case insensitive query that will check if columns contains target.
        $result = @mysqli_query($dbConnect, $query); //Connect to database, send query and store result.

        if($result) //If the query runs successfully
        {
            //Loop through DB row by row.
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
            {

?>
        <div id="container">
            
            <div class="div-1"> <!-- This displays the image on the left side -->
                <img class="great" src="<?php echo $row['ImagePath'];?>">
            </div>

    
            <div class="div-2"> <!-- This displays general item information on the right side -->
                
                <form>
                        <label for="newTitle">Title: </label><br>
                        <input type="text" value="<?php echo $row['Title'];?>" name="newTitle" id="newTitle" size="30" required><br><br>
                        <label for="newPrice">Price (£): </label><br>
                        <input type="text" value="<?php echo $row['Price'];?>" name="newPrice" id="newPrice" size="30" required><br><br>
                        <label for="newArtist">Artist:  </label><br>
                        <input type="text" value="<?php echo $row['Artist'];?>" name="newArtist" id="newArtist" size="30" required><br><br>
                        <label for="newPublisher">Publisher: </label><br>
                        <input type="text" value="<?php echo $row['Publisher'];?>" name="newPublisher" id="newPublisher" size="30" required><br><br>
                        <label for="newDate">Release Date (YYYY-MM-DD): </label><br>
                        <input type="text" value="<?php echo $row['ReleaseDate'];?>" name="newDate" id="newDate" size="30" required><br><br>
                        <label for="newTime">Running Time (mins): </label><br>
                        <input type="text" value="<?php echo $row['RunningTime'];?>" name="newTime" id="newTime" size="30" required><br><br>
                </form>
                
            </div>

    
            <div class="div-3"> <!-- This displays the item's description on the bottom -->
                <form class="responsive" style="position: relative;">
                    
                        <label for="newDescription">Description:</label><br><br>
                        <textarea rows="10" style="width:100%" name="newDescription" id="newDescription" required><?php echo $row['Description'];?></textarea><br><br>
                    
                </form>
            </div>
            
        </div>
<?php
            }

            mysqli_free_result($result); //Empties out DB results, freeing RAM.

        } 
        else 
        {
            echo '<p>error</p>'; //custom error - this will show for syntax error rather than a php server error message
            echo '<p>' . mysqli_error($dbConnect) . '</p>';
        }

        mysqli_close($dbConnect); //Close connection

?>
    <button type="button" onclick="alert('You have bought the item!')">Buy</button>
    <br>
    <br>
    <br>
    <br>
    
<?php
    
    include 'footer.html'; //Display footer.
    
?>
        
    </body>
    
</html>