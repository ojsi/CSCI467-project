<?php
/*
 * CSCI 467/1 Fall 2021
 * Group 2B
 */

include("credentials.php");	// Store your $username and $password in this file


/**
 * This function connects to the specified database and returns the PDO
 * object for that connection.
 *
 * This function is NOT exception-safe.
 *
 * @param dbname the name of the database
 * @param name the name of the user to login to
 * @param passwd the password for the user
 *
 * @return the PDO object for the established connection (might throw
 * exception!)
 */
function loginToDatabase($dbname, $name, $passwd)
{
	// Connect to the mariadb server like normal
	$dsn = "mysql:host=courses;dbname=" . $dbname;
	$pdo = new PDO($dsn, $name, $passwd); //make new pdo object

	// Set error mode
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	return $pdo;    //return the pdo object that was created
}


try {
	// connect to our internal quote database
	// (NOTE these parameters are correct if the DB exists in your NIU MariaDB account, and
	// if you store your credentials ($username and $password) in a file called 
	// credentials.php)
	$pdoQuoteDB = loginToDatabase($username, $username, $password);

	// connect to external customer info legacy database
	//$pdoCustDB = loginToDatabase(/* TODO how to connect? */);
	echo "<p>Note to self: still need to connect to customer info DB</p>\n"; // FIXME temporary!!!

	echo "<html>\n";
	
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
		echo "<table border='1'>\n";
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

			// Find name of matching sales associate
			$query = "SELECT name FROM SalesAssoc
				  WHERE salesAID = ${resultRow['salesAID']}
				  ;";
			$namesResultSet = $pdoQuoteDB->query($query);
			$namesRow = $namesResultSet->fetch(PDO::FETCH_ASSOC);
			echo "    <td>${namesRow['name']}</td>\n";

			// TODO use customer name instead (from querying customer legacy DB?)
			//echo "    <td>${resultRow['customerEmailAddress']}</td>\n";
			echo "    <td>customer name placeholder</td>\n";

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

			// TODO make this button
			echo "    <td>'review quote' button placeholder</td>\n";

			echo "  </tr>\n";
		}
		echo "</table>\n";
	}

	// each quote can be clicked, bringing up edit menu. Within edit menu:
	//
	// 	line items can be added, edited, removed, or have their price altered
	//
	//	discounts can be applied as either a percent or amount
	//
	//	secret notes can be edited
	//
	//	when done, can either leave unresolved or sanction (disappear from list once
	//	sanctioned)
	//
	// upon sanctioning, quote is emailed to customer with all data except secret notes
	
	echo "</html>\n";
}
catch (PDOexception $e) {
	echo "Connection to database failed: " . $e->getMessage();
}

?>
