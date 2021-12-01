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
        
	//get user ID of new user, save as userNUM
	$rs = $pdo->prepare("SELECT DISTINCT name,passwd,accumComm,address FROM SalesAssoc WHERE salesAID = :ID;");
	$rs->execute(array(':ID'=>$_POST['IDnum']));
	$row = $rs->fetch(PDO::FETCH_BOTH);
	$salesAID = $row[0];

    echo "<h3>Sales Associate ${row['salesAID']}</h3>\n";
    echo "<h3>${row['name']}</h3>";

	
	



}

catch (PDOexception $e) {
	echo "Connection to database failed: " . $e->getMessage();
}

?>
<form action="admin_interface.php"><input type=submit class=button name=return value="Back to Admin Interface"></form> </div>
</pre></body></html>
</body></html>


