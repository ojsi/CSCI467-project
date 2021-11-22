<?php
/*
 * CSCI 467/1 Fall 2021
 * Group 2B
 */

include("credentials.php");	// Store your $username and $password in this file
include("dbLoginFunc.php");

try {
	// NOTE this program assumes the quote is finalized (i.e. it'll change status to
	// sanctioned even if it doesn't make sense)
	if(isset($_POST['quoteID']))
	{
		// database connections
		$pdoQuoteDB = loginToDatabase("courses", $username, $username, $password);

		echo "<html>\n";
		echo "<head>\n";
		echo "<title>Sanctioned Quote ${_POST['quoteID']}</title>\n";
		echo "</head>\n";

		// update the status to sanctioned
		$query = "UPDATE Quote
			  SET status = '2'
			  WHERE quoteID = ?
			  ;";
		$prepared = $pdoQuoteDB->prepare($query);
		$prepared->execute(array($_POST['quoteID']));

		echo "<h1>Quote ${_POST['quoteID']} sanctioned!</h1>\n";

		// TODO do I need to send an email to the customer?

		echo "<a href='interface2.php'>Click here to return to the Finalized Quote interface.</a>\n";

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
