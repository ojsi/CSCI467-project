<html><head>
	<title>Edit Associate Info</title>
</head>

<body>
<?php

include("login.php");	

try {

	//connect to internal db
	$dsn = "mysql:host=courses;dbname=z1842318";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
	
	// get row of requested quote
	$query = "SELECT * FROM SalesAssoc WHERE salesAID = ?;";
	$rs = $pdo->prepare($query);
	$rs->execute(array($_GET['IDnum']));
	$rows = $rs->fetch(PDO::FETCH_ASSOC);
	
    echo "<h3>Updating Information for Sales Associate: ${rows['salesAID']}, ${rows['name']}</h3>\n";

	//show and update name
	echo "<form method='GET'>";
	echo "<label for='name'>Associate Name: </label>";
	echo "<input type='text' id='name' name='name' value='${rows['name']}'/> &nbsp";
	echo "<input type='submit' class ='button'  value='Update'/>";
	echo "</form>";

	//show and update password
	echo "<form method='GET'>";
	echo "<label for='passwd'>Associate Password: </label>";
	echo "<input type='text' id='passwd' name='passwd' value='${rows['passwd']}'/> &nbsp";
	echo "<input type='submit' class ='button'  value='Update'/>";
	echo "</form>";
	
	//show and update accumulated commission
	echo "<form method='GET'>";
	echo "<label for='passwd'>Accumulated Commission: </label>";
	echo "<input type='text' id='comm' name='comm' value='${rows['accumComm']}'/> &nbsp";
	echo "<input type='submit' class ='button'  value='Update'/>";
	echo "</form>";

	//show and update address
	echo "<form method='GET'>";
	echo "<label for='passwd'>Associate Address: </label>";
	echo "<input type='text' id='addr' name='addr' value='${rows['address']}'/> &nbsp";
	echo "<input type='submit' class ='button'  value='Update'/>";
	echo "</form>";


}

catch (PDOexception $e) {
	echo "Connection to database failed: " . $e->getMessage();
}

?>
<form action="admin_interface.php"><input type=submit class=button name=return value="Back to Admin Interface"></form> </div>

</body></html>


