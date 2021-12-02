<html><head>
	<title>Edit Associate Info</title>
</head>

<body>
<?php

include("credentials.php");	

try {

	//connect to internal db
	$dsn = "mysql:host=courses;dbname=z1842318";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if(isset($_GET['name'])) {
        $query = "UPDATE SalesAssoc SET name='{$_GET['name']}',
                                        passwd='{$_GET['passwd']}',
                                        accumComm='{$_GET['comm']}',
                                        address='{$_GET['addr']}'
                                        WHERE salesAID = '{$_GET['IDnum']}';";
	    $rs = $pdo->prepare($query);
	    $res = $rs->execute();
    }      
	
	// get row of requested quote
	$query = "SELECT * FROM SalesAssoc WHERE salesAID = ?;";
	$rs = $pdo->prepare($query);
	$rs->execute(array($_GET['IDnum']));
	$rows = $rs->fetch(PDO::FETCH_ASSOC);
	
    echo "<h3>Updating Information for Sales Associate: ${rows['salesAID']}, ${rows['name']}</h3>\n";

	//show and update name
	echo "<form method='GET' action='./edit_assoc.php'>";
	echo "<label for='name'>Associate Name: </label>";
	echo "<input type='text' id='name' name='name' placeholder='default text' value='${rows['name']}'/> &nbsp";
	echo "<br>";
	//echo "<input type='submit' class ='button'  value='Update'/>";
	//echo "</form>";

	//show and update password
	//echo "<form method='GET'>";
	echo "<label for='passwd'>Associate Password: </label>";
	echo "<input type='text' id='passwd' name='passwd' placeholder='default text' value='${rows['passwd']}'/> &nbsp";
	echo "<br>";
	//echo "<input type='submit' class ='button'  value='Update'/>";
	//echo "</form>";
	
	//show and update accumulated commission
	//echo "<form method='GET'>";
	echo "<label for='passwd'>Accumulated Commission: </label>";
	echo "<input type='text' id='comm' name='comm' placeholder='default text' value='${rows['accumComm']}'/> &nbsp";
	echo "<br>";
	//echo "<input type='submit' class ='button'  value='Update'/>";
	//echo "</form>";

	//show and update address
	//echo "<form method='GET'>";
	echo "<label for='passwd'>Associate Address: </label>";
	echo "<input type='text' id='addr' name='addr' placeholder='default text' value='${rows['address']}'/> &nbsp";
	echo "<input type='hidden' name='IDnum' value='${rows['salesAID']}'/>";
	echo "<input type='submit' class ='button'  value='Update'/>";
	echo "<br>";
	echo "</form>";
}

catch (PDOexception $e) {
	echo "Connection to database failed: " . $e->getMessage();
}

?>
<form action="admin_interface.php"><input type=submit class=button name=return value="Back to Admin Interface"></form> </div>
</pre></body></html>
</body></html>