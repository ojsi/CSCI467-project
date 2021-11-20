<?php
/*
 * CSCI 467/1 Fall 2021
 * Group 2B
 */

include("credentials.php");	// Store your $username and $password in this file
include("dbLoginFunc.php");

try {
	if(isset($_GET['quoteID']))
	{
		// database connections
		$pdoQuoteDB = loginToDatabase("courses", $username, $username, $password);
		$pdoCustDB = loginToDatabase("blitz.cs.niu.edu", "csci467", "student", "student");

		echo "<html>\n";
		echo "<head>\n";
		echo "<title>Reviewing Quote ${_GET['quoteID']}</title>\n";
		echo "</head>\n";

		echo "<h2>Reviewing Quote ${_GET['quoteID']}</h2>\n";

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
	else
	{
		echo "<p>Error: quote ID not set.</p>\n";
	}
}
catch (PDOexception $e) {
	echo "Connection to database failed: " . $e->getMessage();
}

?>
