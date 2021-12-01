<html><head>
	<title>Admin Interface</title>
</head>

<body>
<?php
	
//include login
include("login.php");
include("functions.php");

try {
	//connect to internal db
	$dsn = "mysql:host=courses;dbname=z1842318";
	$pdo = new PDO($dsn, $username, $password);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	//sales assoc or quote - default to sales assoc
	echo "<form method='POST'>";
	echo "<input type=radio name='showTable' checked='checked' value='salesAssoc'>Sales Associates";
	echo "<input type=radio name='showTable' value='quote'>Quotes";
	echo "</form>";

	//get sales associates from DB
	$rs = $pdo->query("SELECT DISTINCT salesAID,name,accumComm,address FROM SalesAssoc ;");
	if(!$rs){echo"ERROR in Sales Associate Database"; die();}
	$rows = $rs->fetchALL(PDO::FETCH_ASSOC);
	

	//show table
	echo " <table border='0' cellpadding='10'><tr><th> Associate ID </th><th> Name </th><th> Commission </th><th> Address </th>";

	if (empty($rows)) {
		echo "None.";
	 } else {
		// output data of each row
		foreach($rows as $rows) {
			echo "  <tr>\n";
			echo "    <td>${rows['salesAID']}</td>";
			echo "    <td>${rows['name']}</td>";
			echo "    <td>${rows['accumComm']}</td>";
			echo "    <td>${rows['address']}</td>";

			//edit button
			echo "<form action='./edit_assoc.php' method='POST'>";
			echo "<input type='hidden' name='salesID' value='${rows['salesAID']}'/>";
			echo "<td><input type='submit' value='Edit' id='edit'/></td>";
			echo "</form>";
			
			//delete sales assoc
			echo "<form action='./edit_assoc.php' method='POST'>";
			echo "<input type='hidden' name='IDnum' value='${rows['salesAID']}'/>";
			echo "<td><input type='submit' value='Delete' id='delete'/></td>";
		}
	}


	
	echo "</table>";	

	//quote table



	
}
catch(PDOexception $e) {
	echo "Connection to database failed: " . $e->getMessage();
}

?>

</pre></body></html>
</body></html>


