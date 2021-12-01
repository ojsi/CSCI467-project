<?php
/*
 * CSCI 467/1 Fall 2021
 * Group 2B
 */

include("credentials.php");	// Store your $username and $password in this file
include("dbLoginFunc.php");

try {

		// database connections
		$pdoQuoteDB = loginToDatabase("courses", $username, $username, $password);
		$pdoCustDB = loginToDatabase("blitz.cs.niu.edu", "csci467", "student", "student");

        

        $query = "SELECT MAX(quoteID) as quoteID from Quote;";
        $maxQuoteResult = $pdoQuoteDB->query($query);
        $quoteRow = $maxQuoteResult->fetch(PDO::FETCH_ASSOC);
        $quoteID = $quoteRow['quoteID'];

		echo "<html>\n";
		echo "<head>\n";
		echo "<title>Creating Quote $quoteID</title>\n";
		echo "</head>\n";

		// button to return to interface main page
		echo "<form action='CustomerAccess.php'>\n";
		echo "  <input type='submit' value='Return to Interface 1'>\n";
		echo "</form>\n";

		echo "<h1>Creating Quote $quoteID</h1>\n";

		// get row of requested quote
		$query = "SELECT * FROM Quote
			  WHERE quoteID = ?
			  ;";
		$quotesPrepared = $pdoQuoteDB->prepare($query);
		$quotesPrepared->execute(array($quoteID));
		$quoteRow = $quotesPrepared->fetch(PDO::FETCH_ASSOC);

		// display all customer info
		$query = "SELECT * FROM customers
			  WHERE id = ?
			  ;";
		$customersPrepared = $pdoCustDB->prepare($query);
		$customersPrepared->execute(array($quoteRow['customerID']));
		$customerRow = $customersPrepared->fetch(PDO::FETCH_ASSOC);
		echo "<address>\n";
		echo "  ${customerRow['name']}<br/>\n";
		echo "  ${customerRow['street']}<br/>\n";
		echo "  ${customerRow['city']}<br/>\n";
		echo "  ${customerRow['contact']}<br/>\n";
		echo "</address>\n";

		// display email for quote

		// allow updating of the email
        echo "<form method='POST' name='cusContact'>\n";
        echo "<p>Email: ${quoteRow['cusContact']}</p>\n";
        echo "<h2>Add an Email:</h2>\n";
        echo "  <input type name='EmailText' cols='40' rows='5'>${quoteRow['cusContact']}</input>\n";
        echo "  <input type='submit' name='updateEmail' id='updateEmail' value='save changes'/>\n";
        echo "</form>\n";
		if(isset($_POST['updateEmail']))
		{
			$query = "UPDATE Quote 
				  SET cusContact = :cusContact
				  WHERE quoteID = :quoteID";
			$insertNewLineItem = $pdoQuoteDB->prepare($query);
			$insertNewLineItem->execute(array(
				':cusContact' => $_POST['EmailText'],
				':quoteID' => $quoteID
			));

			// Unset variable to prevent duplicate events (e.g. on refresh)
			unset($_POST['updateEmail']);
		}


		/**********************************************************************
		 * Line Item section
		 **********************************************************************/
		echo "<h2>Line Items:</h2>\n";

		// apply discount from previous form submission to line items 
		if(isset($_POST['applyDiscount']) && isset($_POST['discountType']))
		{
			if($_POST['discountType'] == 'percent')
			{
				// convert e.g. 10 as discount value to 0.90
				$priceMultiplier = 1 - ($_POST['discountValue'] / 100);
				if ($priceMultiplier < 0)
				{
					$priceMultiplier = 0;	// avoid negative prices
				}
				
				// apply percent discount to ALL line items for this quote
				$query = "UPDATE LineItem
					  SET price = price * $priceMultiplier
			  		  WHERE quoteID = ?
			  		  ;";
				$pricesPrepared = $pdoQuoteDB->prepare($query);
				$pricesPrepared->execute(array($quoteID));
			}
			else if ($_POST['discountType'] == 'amount')
			{
				$discountRemaining = $_POST['discountValue'];

				// get array of all line item prices
				$query = "SELECT lineID, price FROM LineItem
					  WHERE quoteID = ?
					  ;";
				$pricesPrepared = $pdoQuoteDB->prepare($query);
				$pricesPrepared->execute(array($quoteID));
				$pricesRows = $pricesPrepared->fetchAll(PDO::FETCH_ASSOC);

				// apply discount down list of line items until none is left
				foreach ($pricesRows as $priceRow)
				{
					if($priceRow['price'] >= $discountRemaining)
					{
						// current line item can consume rest of discount,
						// so reduce its price by that amount and zero
						// the discount remaining

						$newPrice = $priceRow['price'] - $discountRemaining;
						$discountRemaining = 0;
						
						$query = "UPDATE LineItem 
							  SET price = $newPrice
							  WHERE lineID = ${priceRow['lineID']}
							  ;";
						$pdoQuoteDB->query($query);
					}
					else
					{
						// current line item cannot consume rest of discount,
						// so reduce discount remaining by the price of this
						// line item and then zero the price

						$discountRemaining -= $priceRow['price'];

						$query = "UPDATE LineItem 
							  SET price = 0
							  WHERE lineID = ${priceRow['lineID']}
							  ;";
						$pdoQuoteDB->query($query);
					}
				}
			}

			// Unset variable to prevent duplicate events (e.g. on refresh)
			unset($_POST['applyDiscount']);
		}

		// allow insertion of new (empty) line items
		echo "<form method='POST'>\n";
		echo "  <input type='submit' name='newLineItem' id='newLineItem' value='new line item'/>\n";
		echo "</form>\n";
		if(isset($_POST['newLineItem']))
		{
			$query = "INSERT INTO LineItem (quoteID,description,price) VALUES(?,'',0.0);";
			$insertNewLineItem = $pdoQuoteDB->prepare($query);
			$insertNewLineItem->execute(array($quoteID));

			// Unset variable to prevent duplicate events (e.g. on refresh)
			unset($_POST['newLineItem']);
		}

		// allow deletion of line items
		if(isset($_POST['deleteLineItem']))
		{
			$query = "DELETE FROM LineItem 
				  WHERE lineID = ?";
			$insertNewLineItem = $pdoQuoteDB->prepare($query);
			$insertNewLineItem->execute(array($_POST['lineID']));

			// Unset variable to prevent duplicate events (e.g. on refresh)
			unset($_POST['deleteLineItem']);
		}

		// allow updating of line items
		if(isset($_POST['updateLineItem']))
		{
			$query = "UPDATE LineItem 
				  SET description = :newDescrip, price = :newPrice
				  WHERE lineID = :lineID";
			$insertNewLineItem = $pdoQuoteDB->prepare($query);
			$insertNewLineItem->execute(array(
				':newDescrip' => $_POST['descrip'],
				':newPrice' => $_POST['price'],
				':lineID' => $_POST['lineID']
			));

			// Unset variable to prevent duplicate events (e.g. on refresh)
			unset($_POST['updateLineItem']);
		}

		// now that all line item DB changes are made, list all line items from DB
		$query = "SELECT * FROM LineItem
			  WHERE quoteID = ?
			  ;";
		$lineItemsPrepared = $pdoQuoteDB->prepare($query);
		$lineItemsPrepared->execute(array($quoteID));
		$lineItemsRows = $lineItemsPrepared->fetchAll(PDO::FETCH_ASSOC);
		foreach ($lineItemsRows as $lineItemRow)
		{
			echo "<form method='POST' name='lineItem${lineItemRow['lineID']}'>\n";
			echo "  <input type='hidden' name='lineID' value='${lineItemRow['lineID']}'>\n";
			echo "  <input type='text' name='descrip' value='${lineItemRow['description']}'/>\n";
			echo "  <input type='text' name='price' value='${lineItemRow['price']}'/>\n";
			echo "  <input type='submit' name='updateLineItem' id='updateLineItem' value='save changes'/>\n";
			echo "  <input type='submit' name='deleteLineItem' id='deleteLineItem' value='delete'>\n";
			echo "</form>\n";
		}


		/**********************************************************************
		 * Secret Notes section
		 **********************************************************************/
		echo "<h2>Secret Notes:</h2>\n";

		// allow updating of secret notes
		if(isset($_POST['updateSecretNotes']))
		{
			$query = "UPDATE Quote 
				  SET sNotes = :newSecretNotes
				  WHERE quoteID = :quoteID";
			$insertNewLineItem = $pdoQuoteDB->prepare($query);
			$insertNewLineItem->execute(array(
				':newSecretNotes' => $_POST['secretNotesText'],
				':quoteID' => $quoteID
			));

			// Unset variable to prevent duplicate events (e.g. on refresh)
			unset($_POST['updateSecretNotes']);
		}

		// now that all secret note DB changes are made, display secret notes from DB
		$query = "SELECT sNotes FROM Quote
			  WHERE quoteID = ?
			  ;";
		$secretNotesPrepared = $pdoQuoteDB->prepare($query);
		$secretNotesPrepared->execute(array($quoteID));
		$secretNotesRow = $secretNotesPrepared->fetch(PDO::FETCH_ASSOC);
		echo "<form method='POST' name='secretNotes'>\n";
		echo "  <textarea name='secretNotesText' cols='40' rows='5'>${secretNotesRow['sNotes']}</textarea>\n";
		echo "  <input type='submit' name='updateSecretNotes' id='updateSecretNotes' value='save changes'/>\n";
		echo "</form>\n";


		/**********************************************************************
		 * Discounts section
		 **********************************************************************/
		echo "Discount: <form method='POST'>\n";
		echo "  <input type='number' name='discountValue' step='0.01' min='0' value='0'>\n";
		echo "  <br/>\n";
		echo "  <input type='radio' name='discountType' value='percent' checked>percent (e.g. \"10\" for 10% discount)</input>\n";
		echo "  <input type='radio' name='discountType' value='amount'>amount</input>\n";
		echo "  <br/>\n";
		echo "  <input type='submit' name='applyDiscount' id='applyDiscount' value='apply'/>\n";
		echo "</form>\n";

		// Sum up all line items for this quote and display the total price
		$query = "SELECT price FROM LineItem
			  WHERE quoteID = ?
			  ;";
		$pricesPrepared = $pdoQuoteDB->prepare($query);
		$pricesPrepared->execute(array($quoteID));
		$pricesRows = $pricesPrepared->fetchAll(PDO::FETCH_ASSOC);
		$totalPrice = 0;
		foreach ($pricesRows as $pricesRow)
		{
			$totalPrice += $pricesRow['price'];
		}
		echo "Total price: $$totalPrice\n";

		echo "<br/><br/><br/>\n";
		//	when done, can either leave unresolved or sanction (disappear from list once
		//	sanctioned)
		//
		// upon sanctioning, quote is emailed to customer with all data except secret notes

		// button to sanction quote
		echo "<form action='sanctionQuote.php' method='POST'>\n";
		echo "  <input type='hidden' name='quoteID' value=$quoteID/>\n";
		echo "  <input type='submit' id='sanctionQuote' value='Sanction Quote'>\n";
		echo "</form>\n";

		// button to return to interface main page
		echo "<form action='interface2.php'>\n";
		echo "  <input type='submit' value='Return without Sanctioning'>\n";
		echo "</form>\n";

		echo "</html>\n";
	
}
catch (PDOexception $e) {
	echo "Connection to database failed: " . $e->getMessage();
}

?>