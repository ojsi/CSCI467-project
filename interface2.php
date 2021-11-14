<?php
/*
 * CSCI 467/1 Fall 2021
 * Group 2B
 */

try {
	// connect to our internal quote database
	$pdo = //TODO how to connect?
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	// display all finalized quotes
	$query = "SELECT * FROM Quote
		WHERE status = 'finalized'
		;";
	$resultSet = $pdo->query($query);
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
			echo "    <td>${resultRow['creationDate']}</td>\n";

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
