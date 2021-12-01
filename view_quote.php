<html><head>
	<title>View Quote/title>
</head>

<body>
<?php

include("login.php");	

try {

	//connect to internal db
	$dsn = "mysql:host=courses;dbname=z1842318";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
	$rs = $pdo->prepare("SELECT DISTINCT name,passwd,accumComm,address FROM SalesAssoc WHERE quoteID = :ID;");
	$rs->execute(array(':ID'=>$_GET['IDnum']));
	$rows = $rs->fetch(PDO::FETCH_BOTH);

	foreach($rows as $rows) {
		echo "  <tr>\n";
		echo "    <td>${rows['quoteID']}</td>";
		echo "    <td>${rows['status']}</td>";
		echo "    <td>${rows['procDateTime']}</td>";
		echo "    <td>${rows['sNotes']}</td>";
	}


}

catch (PDOexception $e) {
	echo "Connection to database failed: " . $e->getMessage();
}

?>
<form action="admin_interface.php"><input type=submit class=button name=return value="Back to Admin Interface"></form> </div>
</pre></body></html>
</body></html>