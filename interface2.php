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
	
	// display all finalized quotes
	$query = "SELECT * FROM Quote
		WHERE status = '1'
		;";	// status 1 = 'finalized'
	$resultSet = $pdoQuoteDB->query($query);
	if (empty($resultSet))
	{
		echo "<p>No finalized quotes at this time.</p>\n";
	}
	else
	{
		echo "<table>\n";
		foreach($resultSet as $resultRow)
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
			echo "    <td>${resultRow['creationDate']}</td>\n"; // FIXME does this exist?

			// TODO use sales associate name instead (from foreign key match)
			//echo "    <td>${resultRow['salesAssociateID']}</td>\n";
			echo "    <td>sales associate name placeholder</td>\n";

			// TODO use customer name instead (from querying customer legacy DB?)
			//echo "    <td>${resultRow['customerEmailAddress']}</td>\n";
			echo "    <td>customer name placeholder</td>\n";

			// TODO sum up all line item prices
			echo "    <td>total price placeholder</td>\n";

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
}
catch (PDOexception $e) {
	echo "Connection to database failed: " . $e->getMessage();
}

?>
