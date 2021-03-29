<?php
    // Start the session
    session_start();
    require('databaseConnect.php');

    //Redirect user if they are not logged in.
    if(!isset($_SESSION['ID']))
    { 
        header("Location: index.html");
        exit;
    }

    //If page reloads (when calling deleteItem function), $_SESSION['productID'] will not become null.
    if(isset($_GET['productID'])) 
    {
        $_SESSION['productID'] = $_GET['productID'];
    }

?>

<?php
    
    //Note: This must be above output script to function...
    
    /*------------------------------------------------DELETE ITEM------------------------------------------------------*/

        //Delete an item when delete button is pressed.
        if($_SERVER['REQUEST_METHOD'] == "GET" and isset($_GET['deleteButton']))
        {


            $query = "DELETE FROM MusicTable WHERE ProductID = '{$_SESSION['productID']}'"; //Delete line with the current email address.

            mysqli_query($dbConnect, $query); //Connect to database and send query.

            if(mysqli_affected_rows($dbConnect) > 0) //Check that a row was changed.
            {
                if ($_SESSION["admin"] == 1)
                {
                    header('Location: adminMonitoring.php'); //Send admin back to adminMonitoring.
                    exit; //Prevent further code execution.
                }
                else
                {
                    header('Location: mySales.php'); //Send user back to mySales.
                    exit; //Prevent further code execution.
                }
            } 
            else 
            {
                echo '<p>Error, see below for details...</p>'; //Custom error - just in case. Should never be triggered.
                echo '<p>' . mysqli_error($dbConnect) . '</p>';
            }

            mysqli_free_result($result); //Empties out DB results, freeing RAM.

            unset($_POST['deleteButton']); //To avoid accidentally deleting entry on page reload (I don't know if this is actually a problem).

        }
    
?>

<!DOCTYPE html>

<html>
    
<head>
    <meta name="viewport" content="initial-scale=1.0; maximum-scale=1.0; width=device-width;">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css"> <!--Links the CSS file.-->
</head>
    
<body>

<?php

   //Dynamic navbar that changes based on if the user is an admin.
   if ($_SESSION["admin"] == 1)
   {
       
?>
        <nav>
            <ul>
                <li>
                    <a href="adminMonitoring.php">Admin Monitoring</a>
                </li>
                <li>
                    <a href="logout.php">Logout</a>      
                </li>
            </ul>
        </nav>
<?php
       
   }
   else
   {
       
?>
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
<?php
       
   }

?>

    <h1>Edit Item</h1>
    
    <br> 
    
<?php
    
    
    /*------------------------------------------------UPLOAD IMAGE------------------------------------------------------*/
    
        //Upload an image when upload button is pressed.
        if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['uploadImageButton']))
        {

            $uploadOk = 1; //Boolean used to check if it is okay to upload the file.
            $imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]),PATHINFO_EXTENSION)); //Stores file extension. Used to verify that the file is an image.

            // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);

            if($check !== false) 
            {
                $uploadOk = 1;
            } 
            else 
            {    
                echo "<p>File is not an image.</p>";
                $uploadOk = 0;
            }

            // Check file size
            if ($_FILES["fileToUpload"]["size"] > 500000) 
            {
                echo "<p>Sorry, your file is too large.</p>";
                $uploadOk = 0;
            }

            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) 
            {
                echo "<p>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</p>";
                $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) 
            {
              echo "<br> <p>Sorry, your file was not uploaded.</p>";
            } 
            else // if everything is ok, try to upload file
            { 
                $newfilename = "images/" . round(microtime(true)) . '.' . $imageFileType; //Give file a new name based on current time, then add extension.

                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $newfilename)) //Upload the file to the database.
                {
                    
                    $query = "SELECT ImagePath FROM MusicTable WHERE ProductID = '{$_SESSION['productID']}'"; //Case insensitive query that will check if columns contains target.
                    $result = @mysqli_query($dbConnect, $query); //Connect to database, send query and store result.

                    if($result) //If the query runs successfully
                    {
                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) //Loop through DB row by row.
                        {
                            unlink($row["ImagePath"]); //Delete original image file.
                        }
                    }
                    
                    $query = "UPDATE MusicTable SET ImagePath = '$newfilename' WHERE ProductID = '{$_SESSION['productID']}'"; //Query to change filepath in the database.
                    $result = @mysqli_query($dbConnect, $query); //Connect to database, send query and store result.

                                if($result) //If the query runs successfully
                                {
                                    mysqli_free_result($result); //Empties out DB results, freeing RAM.
                                    header('Location: editItem.php'); //Reload page.
                                    exit; //Prevent further code execution.
                                }
                                else
                                {
                                    echo "<p>Sorry, there was an error uploading your file.<p>";
                                }
                } 

            }

            unset($_POST['uploadImageButton']); //To avoid accidentally reuploading an image on page reload.

        }
    
?>

<script>
    
    //Javascript function that asks user for confirmation of item deletion.
    function confirmDelete()
    {
        var confirmation = confirm("Are you sure you want to permanently delete this item?");
        
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

        //This will update item details (excluding description and image).
        if(isset($_GET['newTitle']))
        {
            $title = mysqli_real_escape_string($dbConnect, $_GET['newTitle']);
            $price = mysqli_real_escape_string($dbConnect, $_GET['newPrice']);
            $artist = mysqli_real_escape_string($dbConnect, $_GET['newArtist']);
            $publisher = mysqli_real_escape_string($dbConnect, $_GET['newPublisher']);
            $releaseDate = mysqli_real_escape_string($dbConnect, $_GET['newDate']);
            $runningTime = mysqli_real_escape_string($dbConnect, $_GET['newTime']);
            
            $query = "UPDATE MusicTable SET Title = '$title', Price = '$price', Artist = '$artist', Publisher = '$publisher', ReleaseDate = '$releaseDate', RunningTime = '$runningTime' WHERE ProductID = '{$_SESSION['productID']}'";

            mysqli_query($dbConnect, $query); //Connect to database, send query and store result.

            if(mysqli_affected_rows($dbConnect) > 0) //Check that a row was changed.
            {
                echo "<p><b>Changes Saved!</b></p>";                
            } 
            else 
            {
                echo "<p><b>No new data or incorrect data format. Changes not saved.</b></p>";
            }

        }
    
        //This will update item description.
        if(isset($_GET['newDescription']))
        {
            $description = mysqli_real_escape_string($dbConnect, $_GET['newDescription']);
            
            $query = "UPDATE MusicTable SET Description = '$description' WHERE ProductID = '{$_SESSION['productID']}'";

            mysqli_query($dbConnect, $query); //Connect to database, send query and store result.

            if(mysqli_affected_rows($dbConnect) > 0) //Check that a row was changed.
            {
                echo "<p><b>Changes Saved!</b></p>";                
            } 
            else 
            {
                echo "<p><b>No new data or field is empty. Changes not saved.</b></p>";
            }

        }

        $query = "SELECT * FROM MusicTable WHERE ProductID = '{$_SESSION['productID']}'"; //Case insensitive query that will check if columns contains target.
        $result = @mysqli_query($dbConnect, $query); //Connect to database, send query and store result.

        if($result) //If the query runs successfully
        {
            //Loop through DB row by row.
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
            {

?>
        
		
		<div id="container">
           
            <div class="div-1">
                <img class="great" src="<?php echo $row['ImagePath'];?>">
                <br>
				
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data"> <!-- Form for the item's image -->
                    
				    <br>
                    <input type="file" name="fileToUpload" id="fileToUpload">
                    <label for="fileToUpload">Choose image</label>
                    <br>
                    <button type="submit" name="uploadImageButton">Upload New Image</button>
                      
                </form>
            </div>
    
            <div class="div-2">
                
                <br>
                <br>
                <br>
                <br>
                
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="get"> <!-- Form for general item information -->
                    
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
                        
                        <button type="submit">Submit</button>
                    
                </form>
            </div>
        
			
            <div class="div-3">
                <form class="responsive" style="position: relative;" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="get"> <!-- Form for item's description -->
                    
					<br>
					<br>
                    <label for="newDescription"> Description: </label>
                    <br>
                    <br>
                    <textarea rows="10" style="width:100%" name="newDescription" id="newDescription" required><?php echo $row['Description'];?></textarea><br><br>
                    <button type="submit" style="margin-top: 2px;">Submit</button>
                    
                </form>
            </div>
		
		</div>
			
			
			
<?php
            }

            mysqli_free_result($result); //Empties out DB results, freeing RAM.

        } 
        else 
        {
            echo '<p>error</p>'; //Custom error - just in case. Should never be triggered.
            echo '<p>' . mysqli_error($dbConnect) . '</p>';
        }

        mysqli_close($dbConnect); //Close connection

?>
    
    <br>
    <br>
    <br>
    <br>
    
    <div>
        <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" onsubmit="return confirmDelete();">
            <div class="my-padding-64">
                <button type="submit" name="deleteButton" style="color: white; background-color: red; border-style: solid">Delete Item</button>
            </div>
        </form>
    </div>
    
    <br>
    <br>
    <br>
    <br>
            
<?php
    
    include 'footer.html'; //Display footer.
    
?>

</body>
    
</html>