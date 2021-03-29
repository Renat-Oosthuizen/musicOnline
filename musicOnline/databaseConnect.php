<?php

//mysql_connect.php
//contains database login info

//define login info as constant so that they can't be changed later in the script

define('USERNAME', '');
define('PWRD', '');
define('HOSTNAME', '');
define('DBNAME', '');

//database connection
//@ supresses the error that would show username
$dbConnect = @mysqli_connect(HOSTNAME, USERNAME, PWRD, DBNAME) OR die('Could not connnect to db ' . mysqli_connect_error());

?>