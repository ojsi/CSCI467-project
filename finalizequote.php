<?php
/*
 * CSCI 467/1 Fall 2021
 * Group 2B
 */

include("credentials.php");
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
		echo "<title>Quote has been created ${_POST['quoteID']}</title>\n";
		echo "</head>\n";

		// update the status to sanctioned
		$query = "UPDATE Quote
			  SET status = '1'
			  WHERE quoteID = ?
			  ;";
		$prepared = $pdoQuoteDB->prepare($query);
		$prepared->execute(array($_POST['quoteID']));

		echo "<h1>Finalized the quote!</h1>\n";

		//echo "<a href='CustomerAccess.php'>Click here to return to the Quote Creation interface.</a>\n";

		echo "<a href='logout.php'>Click here to log out of the system.</a>\n";


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
