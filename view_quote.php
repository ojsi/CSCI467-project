<html><head>
	<title>View Quote</title>
</head>

<body>
<?php

include("login.php");
include("functions.php");	

try {

	/* DATA BASE CONNCETIONS */
	//connect to internal db
	$dsn = "mysql:host=courses;dbname=z1842318";
	$pdo = new PDO($dsn, $username, $password);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	//connect to customer db
	$dsn2 = "mysql:host=blitz.cs.niu.edu;dbname=csci467";
	$pdo2 = new PDO($dsn2, "student", "student");
	$pdo2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
	//get info from Database
	$quote_info = get_information("SELECT * FROM Quote WHERE quoteID={$_GET['IDnum']};",$pdo);
	$line_info = get_information("SELECT * FROM LineItem WHERE quoteID={$_GET['IDnum']};",$pdo);
	$sa_info = get_information("SELECT salesAID,name FROM SalesAssoc WHERE salesAID={$quote_info[0]['salesAID']};",$pdo);
	$cus_info = get_information("SELECT * FROM customers WHERE id={$quote_info[0]['customerID']};",$pdo2);

	$cusName = $cus_info[0]['name'];
	

	echo "<h2>Viewing Quote Details for $cusName</h2>\n";

	echo $cus_info[0]["city"] . "</br>";
    echo $cus_info[0]["street"] . "</br>";
    $number = $cus_info[0]["contact"];
    echo "<div id=\"phone\">$number</div>";
    echo "</br>";

	$email = $quote_info[0]["cusContact"];
    if(!is_numeric($email)) {
		echo "Email: <input value=$email readonly></input></br>";
	}

	$quoteComm = $quote_info[0]['commission'];
	echo "<br>Quote Commission: " . $quoteComm;

	echo "<h3><b>Line Items:</b></h3>";
    echo "<table border='0' cellpadding='10'><tr><th> Description </th><th> ID </th>";
	$cost = 0;
    foreach($line_info as $item)
    {
		echo "<tr>\n";
        echo "<td>";
        echo $item["description"];
        echo "</td>";
        echo "<td>";
        echo $item["lineID"];
        echo "</td>";
	}
	echo "<table/>";

	echo "<h3><b>Notes:</b></h3>";
	$sNotes = $quote_info[0]['sNotes'];
	echo $sNotes;
	echo "<br>";
	echo "<br>";
	

	$saName = $sa_info[0]['name'];
	echo "<br>";
	echo "Sale Associate: " . $saName;




}

catch (PDOexception $e) {
	echo "Connection to database failed: " . $e->getMessage();
}

?>
<div><form action="admin_interface.php"><input type=submit class=button name=return value="Back to Admin Interface"></form> </div>
</pre></body></html>
</body></html>