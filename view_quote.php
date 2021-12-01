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

    //connect to customer db

    
    //"sort by" params, default sort by quote id, default to show all

    //quote table 
	//get quote from DB
	$rs = $pdo->query("SELECT DISTINCT quoteID FROM Quote ;");
	if(!$rs){echo"ERROR in Sales Associate Database"; die();}
	$rows = $rs->fetchALL(PDO::FETCH_ASSOC);
	



}

catch (PDOexception $e) {
	echo "Connection to database failed: " . $e->getMessage();
}

?>
<form action="admin_interface.php"><input type=submit class=button name=return value="Back to Admin Interface"></form> </div>
</pre></body></html>
</body></html>