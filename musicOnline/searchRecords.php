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
    
<script>
    //If search field is empty, value will be changed to something that will return no results from DB.
    function checkEmpty()
    {
        if (document.getElementById('vynilSearch').value == '')
            {
                document.getElementById('vynilSearch').value = '                                ';
            }
    }
</script>
    <!--Navigation Bar-->    
    <nav>
        <ul>
            <li>
                <a class="active" href="searchRecords.php">Search Records</a>
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
    
    
    <h1>Search Records</h1>
    
    <!--Form-->                
    <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> <!--Send form data to self via get (avoids form resubmission messages on page reload)-->
        <label for="vynilSearch">Search for Vinyls:</label><br><br>
        <div>
            <input type="text" placeholder="title/artist" name="vynilSearch" id="vynilSearch"><br><br>
            <button type="submit" onclick="checkEmpty();">Search</button> <!--Calls the checkEmpty() JS function so that if search field is empty, there will be no matches in DB.-->
            <button type="submit" onclick="document.getElementById('vynilSearch').value = ''">Show All</button> <!--Sets the search field to '' so that DB will return everything.-->
        </div>
    </form>
    
        

    
<?php
    require('databaseConnect.php'); //Provides access to databaseConnect.php during script execution.

    //Variables used.
    $search; //Variable stores the target search string.
    $query; //Variable stores query for the database
    $result; //Variable stored data returned from database.

    if(isset($_GET["vynilSearch"])) //isset check avoids $search being null and then automatically loading everything in the Table when page is first loaded.
    { 

        $search = "%" . $_GET["vynilSearch"] . "%";

        $search = mysqli_real_escape_string($dbConnect, $search); //Prevents SQL injection attacks.

        $query = "SELECT ProductID, Title, Artist FROM MusicTable WHERE Title LIKE '$search' or Artist LIKE '$search'"; //Case insensitive query that will check if columns contains target.
        $result = @mysqli_query($dbConnect, $query); //Connect to database, send query and store result.

        if($result and ($result -> num_rows > 0)) //If the query runs successfully, create headers for Vynil Table.
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
                    <td class="text-left"><?php echo '<a href="item.php?productID='. $row['ProductID'] .'" >'. $row['Title']; ?></td>
                    <td class="text-left"><?php echo '<a href="item.php?productID='. $row['ProductID'] .'" >'. $row['Artist']; ?></td>
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
            echo '<p>No matching items found.</p>'; 
        }

        mysqli_close($dbConnect); //Close connection.

    }
        
    include 'footer.html'; //Display footer.
?>
        
</body>
    
</html>