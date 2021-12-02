<!-- visit page at http://students.cs.niu.edu/~z1842318/admin_interface.php -->

<html><head>
	<title>Admin Interface - Viewing Sales Associates</title>
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

<!-- vswitch between viewing sales associates and quotes -->	
<div class="button">
	<div class="action_btn">
	<button name="submission" class="action_btn" type="submit" value="4" onclick="location.href = 'admin_interface.php';">Show Sales Associates</button>
		<button name="submission" class="action_btn" type="submit" value="4" onclick="location.href = 'admin_interface_quote.php';">Show Quotes</button>
		   <p id="saved"></p>
	<form>  
        <input type='hidden' name='submission' value='4'>
    </form>
</div>
<?php
	
//include login
include("login.php");

try {
	//connect to internal db
	$dsn = "mysql:host=courses;dbname=z1842318";
	$pdo = new PDO($dsn, $username, $password);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


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
			echo "<form action='./edit_assoc.php' method='GET'>";
			echo "<input type='hidden' name='IDnum' value='${rows['salesAID']}'/>";
			echo "<td><input type='submit' value='Edit' id='edit_sales'/></td>";
			echo "</form>";
			
			//delete sales assoc
			echo "<form action='./edit_assoc.php' method='POST'>";
			echo "<input type='hidden' name='delIDnum' value='${rows['salesAID']}'/>";
			echo "<td><input type='submit' value='Delete' id='delete_sales'/></td>";

			if(isset($_POST['name'])) 
			{
				$delquery = "DELETE FROM SalesAssoc WHERE salesAID = :delIDnum";
				$removeSA = $pdoQuoteDB->prepare($query);
				$removeSA->execute(array(':delIDnum' => $_POST['salesAID']));
				unset($_POST['name']);
			}
		}	
	}
	echo "</table>";	

	//add associate
	echo "<h3>Add New Sales Associate</h3>\n";
	echo "<form action='./admin_interface.php' method='POST'>";
	echo "<label for='name'>Name: </label>";
	echo "<input type='text' id='name' name='name'/> &nbsp";
	echo "<label for='passwd'>Create Password: </label>";
	echo "<input type='text' id='passwd' name='passwd''/> &nbsp";
	echo "<input type='submit' class ='button' value='Add New Associate'/>";
	echo "</form>";

	//add new user
	if(isset($_GET['name'])) 
	{
		$query = "INSERT INTO SalesAssoc (name,passwd) VALUES(:name, :pass);";
		$addNewSA = $pdoQuoteDB->prepare($query);
		$addNewSA->execute(array(':name' => $_POST['name'], ':pass' => $_POST['passwd']));
		unset($_POST['name']);
	}

}
catch(PDOexception $e) {
	echo "Connection to database failed: " . $e->getMessage();
}

?>

</pre></body></html>
</body></html>


