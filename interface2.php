<?php
/*
 * CSCI 467/1 Fall 2021
 * Group 2B
 */

include("credentials.php");	// Store your $username and $password in this file
include("dbLoginFunc.php");


try {
	// connect to our internal quote database
	// (NOTE these parameters are correct if the DB exists in your NIU MariaDB account, and
	// if you store your credentials ($username and $password) in a file called 
	// credentials.php)
	$pdoQuoteDB = loginToDatabase("courses", $username, $username, $password);

	// connect to external customer info legacy database
	$pdoCustDB = loginToDatabase("blitz.cs.niu.edu", "csci467", "student", "student");

	echo "<html>\n";
	echo "<head>\n";
	echo "<title>Finalized Quote Interface</title>\n";
	echo "</head>\n";

	echo "<h1>Finalized Quote Interface</h1>\n";
	
	// display all finalized quotes
	$query = "SELECT * FROM Quote
		WHERE status = '1'
		;";	// status 1 = 'finalized'
	$resultSet = $pdoQuoteDB->query($query);
	$resultRows = $resultSet->fetchAll(PDO::FETCH_ASSOC);
	if (empty($resultRows))
	{
		echo "<p>No finalized quotes at this time.</p>\n";
	}
	else
	{
		echo "<table border='1' cellpadding=5>\n";
		foreach($resultRows as $resultRow)
		{
			// make an HTML table row for each finalized quote, with:
			// 	quote ID
			// 	creation date
			// 	sales associate name
			// 	customer name (query customer DB?)
			// 	total price
			// 	a "review quote" button (was called "sanction quote" in video but
			// 			covered both sanctioning and editing)


			echo "  <tr>\n";
			
			echo "    <td>${resultRow['quoteID']}</td>\n";

			//echo "    <td>${resultRow['creationDate']}</td>\n"; // FIXME does this exist?

			// Get name of sales associate
			$query = "SELECT name FROM SalesAssoc
				  WHERE salesAID = ${resultRow['salesAID']}
				  ;";
			$saNamesResultSet = $pdoQuoteDB->query($query);
			$saNamesRow = $saNamesResultSet->fetch(PDO::FETCH_ASSOC);
			echo "    <td>${saNamesRow['name']}</td>\n";

			// Get name of customer
			$query = "SELECT name FROM customers
				  WHERE id = ${resultRow['customerID']}
				  ;";
			$custNamesResultSet = $pdoCustDB->query($query);
			$custNamesRow = $custNamesResultSet->fetch(PDO::FETCH_ASSOC);
			echo "    <td>${custNamesRow['name']}</td>\n";

			// Sum up all line items for this quote and display the total price
			$query = "SELECT price FROM LineItem
				  WHERE quoteID = ${resultRow['quoteID']}
				  ;";
			$pricesResultSet = $pdoQuoteDB->query($query);
			$pricesRows = $pricesResultSet->fetchAll(PDO::FETCH_ASSOC);
			$totalPrice = 0;
			foreach ($pricesRows as $pricesRow)
			{
				$totalPrice += $pricesRow['price'];
			}
			echo "    <td>$$totalPrice</td>\n";

			// Button to review this row's quote for editing and/or sanctioning
			echo "    <td>\n";
			echo "      <form action='./reviewFinalized.php' method='GET'>\n";
			echo "        <input type='hidden' name='quoteID' value='${resultRow['quoteID']}'/>\n";
			echo "        <input type='submit' value='review quote' id='review_quote'/>\n";
			echo "      </form>\n";
			echo "    </td>\n";

			echo "  </tr>\n";
		}
		echo "</table>\n";
	}
	
	echo "</html>\n";
}
catch (PDOexception $e) {
	echo "Connection to database failed: " . $e->getMessage();
}

?>

<form action="index.html"><input type=submit class=button name=return value="Back to Home Page"></form> </div>
