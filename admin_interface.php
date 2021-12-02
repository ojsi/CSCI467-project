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
include("credentials.php");	

try {
	//connect to internal db
	$dsn = "mysql:host=courses;dbname=z1842318";
	$pdo = new PDO($dsn, $username, $password);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	//add new user
	if(isset($_POST['add'])) 
	{
		$query = "INSERT INTO SalesAssoc (name,passwd,accumComm,address) VALUES(:name, :pass, :comm, :addr);";
		$addNewSA = $pdo->prepare($query);
		$return = $addNewSA->execute(array(':name' => $_POST['name'], ':pass' => $_POST['passwd'], ':comm' => $_POST['comm'], ':addr' => $_POST['addr']));
	}

	//kill
	if(isset($_POST['delete'])) 
		{
			$delquery = "DELETE FROM SalesAssoc WHERE salesAID = :delIDnum";
			$removeSA = $pdo->prepare($delquery);			
			$result2 = $removeSA->execute(array(':delIDnum' => $_POST['delIDnum']));
		}

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
			echo "<td><input name='edit' type='submit' value='Edit User' id='view'/></td>";
			echo "</form>";
			
			//delete sales assoc
			echo "<form action='./admin_interface.php' method='POST'>";
			echo "<input type='hidden' name='delIDnum' value='${rows['salesAID']}'/>";
			echo "<td><input name='delete' type='submit' value='Delete' id='delete_sales'/></td>";
			echo "</form>";
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

	echo "<input type='hidden' id='comm' name='comm' value='00.00'>"; 
	echo "<input type='hidden' id='addr' name='addr' value='None Given'>"; 
	echo "<input type='submit' name='add' class ='button' value='Add New Associate'/>";
	echo "<input type='hidden' name='submit' value='0'>"; 
	echo "</form>";

}
catch(PDOexception $e) {
	echo "Connection to database failed: " . $e->getMessage();
}

?>
<form action="index.html"><input type=submit class=button name=return value="Back to Home Page"></form> </div>
</pre></body></html>
</body></html>


