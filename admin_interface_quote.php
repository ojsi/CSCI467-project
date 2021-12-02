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
	/* DATA BASE CONNCETIONS */
	//connect to internal db
	$dsn = "mysql:host=courses;dbname=z1842318";
	$pdo = new PDO($dsn, $username, $password);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	//connect to customer db
	$dsn2 = "mysql:host=blitz.cs.niu.edu;dbname=csci467";
	$pdo2 = new PDO($dsn2, "student", "student");
	$pdo2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	/* DROP DOWN OPTIONS */
	echo "<form action='./admin_interface_quote.php' method='POST'>";

	//get sales associates from DB for sort
	echo "<label for='salesA'> Sales Associate:</label>";
	echo "&nbsp<select name='salesA' value='Sales Associate'</option>&nbsp";
	echo "<option value='salesAID'>Show all</option>"; 
	$rs2 = $pdo->query("SELECT salesAID,name FROM SalesAssoc ORDER BY name;");
	if(!$rs2){echo"ERROR in Sales Associate Database"; die();}
	$rows2 = $rs2->fetchALL(PDO::FETCH_ASSOC);
	foreach ($rows2 as $rows2) {
		echo "<option value=$rows2[salesAID]>$rows2[name]</option>"; 
	}
	echo "</select>";
	echo "&nbsp";
	echo "<input type='submit' class ='button' value='Sort By Parameters'/>";
	echo "<input type='hidden' name='submission' value='0'>"; 
	echo "<input type='hidden' name='status' value='status'>"; 
	echo "<input type='hidden' name='custName' value='custName'>"; 
	echo "</form>";
	echo "<br>";

	//get all customers for sort
	echo "<form action='./admin_interface_quote.php' method='POST'>";
	echo "<label for='custName'> Customer:</label>";
	$rs3 = $pdo2->query("SELECT id,name FROM customers;");
	if(!$rs3){echo"ERROR in Sales Associate Database"; die();}
	$rows3 = $rs3->fetchALL(PDO::FETCH_ASSOC);
	echo "&nbsp<select name='custName' value='Customers'</option>"; 
	echo "<option value='customerID'>Show all</option>";
	foreach ($rows3 as $rows3) {
		echo "<option value=$rows3[id]>$rows3[name]</option>"; 
	}
	echo "</select>";
	echo "&nbsp";
	echo "<input type='submit' class ='button' value='Sort By Parameters'/>";
	echo "<input type='hidden' name='submission' value='1'>"; 
	echo "<input type='hidden' name='salesA' value='salesA'>"; 
	echo "<input type='hidden' name='status' value='status'>"; 
	echo "</form>";
	echo "<br>";
	
	//quote status
	echo "<form action='./admin_interface_quote.php' method='POST'>";
	echo "<label for='status'> Quote Status:</label>";
	echo "&nbsp<select name='status' id='status'>";
		echo "<option value='status'>Show all</option>";
		echo "<option value='0'>In-progress</option>";
		echo "<option value='1'>Finalized</option>";
		echo "<option value='2'>Sanctioned</option>";
		echo "<option value='3'>Ordered</option>";
		echo "<option value='4'>Unresolved</option>";
	echo "</select>"; 

	echo "&nbsp";
	echo "<input type='submit' class ='button' value='Sort By Parameters'/>";
	echo "<input type='hidden' name='submission' value='2'>"; 
	echo "<input type='hidden' name='salesA' value='salesA'>"; 
	echo "<input type='hidden' name='custName' value='custName'>"; 
	echo "</form>";

	/* SHOW QUOTES WITH PARAMETERS */

	
	if(isset($_POST['submission'])){
		$sales=$_POST['salesA'];
		$status=$_POST['status'];
		$cust=$_POST['custName'];
	} 

	if(($_POST['submission']) == '0'){

		$rs = $pdo->query("SELECT * FROM Quote WHERE salesAID=$sales;");
		if(!$rs){echo"ERROR in Database"; die();}
		$rows = $rs->fetchALL(PDO::FETCH_ASSOC);
	
		//show table
		echo " <table border='0' cellpadding='10'><tr><th> Quote ID </th><th> Status </th><th> Date Created </th><th> SA/ID </th><th> C/ID </th><th> Notes </th>";

		if (empty($rows)) {
			echo "None.";
		} else {
			// output data of each row
			foreach($rows as $rows) {
				echo "  <tr>\n";
				echo "    <td>${rows['quoteID']}</td>";
				echo "    <td>${rows['procDateTime']}</td>";
				echo "    <td>${rows['status']}</td>";
				echo "    <td>${rows['sNotes']}</td>";

				//edit button
				echo "<form action='./view_quote.php' method='GET'>";
				echo "<input type='hidden' name='IDnum' value='${rows['quoteID']}'/>";
				echo "<td><input type='submit' value='View Quote' id='view'/></td>";
				echo "</form>";
		
			}

		}

	} elseif (($_POST['submission']) == '1') {

		$rs = $pdo->query("SELECT * FROM Quote WHERE customerID=$cust;");
		if(!$rs){echo"ERROR in Database"; die();}
		$rows = $rs->fetchALL(PDO::FETCH_ASSOC);
	
		//show table
		echo " <table border='0' cellpadding='10'><tr><th> Quote ID </th><th> Status </th><th> Date Created </th><th> SA/ID </th><th> C/ID </th><th> Notes </th>";

		if (empty($rows)) {
			echo "None.";
		} else {
			// output data of each row
			foreach($rows as $rows) {
				echo "  <tr>\n";
				echo "    <td>${rows['quoteID']}</td>";
				echo "    <td>${rows['procDateTime']}</td>";
				echo "    <td>${rows['status']}</td>";
				echo "    <td>${rows['sNotes']}</td>";

				//edit button
				echo "<form action='./view_quote.php' method='GET'>";
				echo "<input type='hidden' name='IDnum' value='${rows['quoteID']}'/>";
				echo "<td><input type='submit' value='View Quote' id='view'/></td>";
				echo "</form>";
		
			}

		}
		
	} elseif (($_POST['submission']) == '2') {

		$rs = $pdo->query("SELECT * FROM Quote WHERE status=$status;");
		if(!$rs){echo"ERROR in Database"; die();}
		$rows = $rs->fetchALL(PDO::FETCH_ASSOC);
	
		//show table
		echo " <table border='0' cellpadding='10'><tr><th> Quote ID </th><th> Status </th><th> Date Created </th><th> SA/ID </th><th> C/ID </th><th> Notes </th>";

		if (empty($rows)) {
			echo "None.";
		} else {
			// output data of each row
			foreach($rows as $rows) {
				echo "  <tr>\n";
				echo "    <td>${rows['quoteID']}</td>";
				echo "    <td>${rows['procDateTime']}</td>";
				echo "    <td>${rows['status']}</td>";
				echo "    <td>${rows['sNotes']}</td>";

				//edit button
				echo "<form action='./view_quote.php' method='GET'>";
				echo "<input type='hidden' name='IDnum' value='${rows['quoteID']}'/>";
				echo "<td><input type='submit' value='View Quote' id='view'/></td>";
				echo "</form>";
		
			}

		}

	} else {
		// SHOW ALL QUOTES
		//get quotes from DB
		$rs = $pdo->query("SELECT DISTINCT * FROM Quote ;");
		if(!$rs){echo"ERROR in Sales Associate Database"; die();}
		$rows = $rs->fetchALL(PDO::FETCH_ASSOC);

		//show table
		echo " <table border='0' cellpadding='10'><tr><th> Quote ID </th><th> Status </th><th> Date Created </th><th> SA/ID </th><th> C/ID </th><th> Notes </th>";

		if (empty($rows)) {
			echo "None.";
		} else {
			// output data of each row
			foreach($rows as $rows) {
				echo "  <tr>\n";
				echo "    <td>${rows['quoteID']}</td>";
				echo "    <td>${rows['status']}</td>";
				echo "    <td>${rows['procDateTime']}</td>";
				echo "    <td>${rows['salesAID']}</td>";
				echo "    <td>${rows['customerID']}</td>";
				echo "    <td>${rows['sNotes']}</td>";

				//edit button
				echo "<form action='./view_quote.php' method='GET'>";
				echo "<input type='hidden' name='IDnum' value='${rows['quoteID']}'/>";
				echo "<td><input type='submit' value='View Quote' id='view'/></td>";
				echo "</form>";
		
			}
		}
	}
	
}

	
catch(PDOexception $e) {
	echo "Connection to database failed: " . $e->getMessage();
}

?>

</pre></body></html>
</body></html>


