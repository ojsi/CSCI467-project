<html><head>
	<title>Admin Interface - Viewing Quotes</title>
</head>
<style>
  .button {
width: 200px;
margin: 0 auto;
display: inline;}

    .action_btn {
width: 200px;
margin: 0 auto;
display: inline;}
</style>
<body>
<div class="button">
	<div class="action_btn">
	<button name="submit" class="action_btn" type="submit" value="submit" onclick="location.href = 'admin_interface.php';">Show Sales Associates</button>
		<button name="submit" class="action_btn" type="submit" value="submit" onclick="location.href = 'admin_interface_quote.php';">Show Quotes</button>
   		<p id="saved"></p>
</div>


<?php
	
//include login
include("login.php");
include("functions.php");

try {
	//connect to internal db
	$dsn = "mysql:host=courses;dbname=z1842318";
	$pdo = new PDO($dsn, $username, $password);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


	//get sales associates from DB
	$rs = $pdo->query("SELECT DISTINCT quoteID,status,procDateTime,sNotes FROM Quote ;");
	if(!$rs){echo"ERROR in Sales Associate Database"; die();}
	$rows = $rs->fetchALL(PDO::FETCH_ASSOC);
	

	//show table
	echo " <table border='0' cellpadding='10'><tr><th> Quote ID </th><th> Status </th><th> Date Created </th><th> Notes </th>";

	if (empty($rows)) {
		echo "None.";
	 } else {
		// output data of each row
		foreach($rows as $rows) {
			echo "  <tr>\n";
			echo "    <td>${rows['quoteID']}</td>";
			echo "    <td>${rows['status']}</td>";
			echo "    <td>${rows['procDateTime']}</td>";
			echo "    <td>${rows['sNotes']}</td>";

			//edit button
			echo "<form action='./view_quote.php' method='GET'>";
			echo "<input type='hidden' name='IDnum' value='${rows['quoteID']}'/>";
			echo "<td><input type='submit' value='View Quote' id='view'/></td>";
			echo "</form>";
			
	
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


