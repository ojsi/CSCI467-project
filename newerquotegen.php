<div class="p-1 btn-group d-flex">
<a href="logout.php" class="btn btn-danger" role="button" target="_self">Logout</a>
</div>
<?php
/*
 * CSCI 467/1 Fall 2021
 * Group 2B
 */
print_r($_POST);
include("credentials.php");	// Store your $username and $password in this file
include("dbLoginFunc.php");


try {

		// database connections
		$pdoQuoteDB = loginToDatabase("courses", $username, $username, $password);
		$pdoCustDB = loginToDatabase("blitz.cs.niu.edu", "csci467", "student", "student");

		session_start(); 

		if(isset($_POST["salesAID"])) {    // check to avoid 'Notice: undefined index'
			if(isset($_SESSION["salesAID"])) {    // check to avoid 'Notice: undefined index'
			   if($_POST["salesAID"] != $_SESSION["salesAID"]) { 
				  // if the variable is set in both but _SESSION differs, update _SESSION
				  $_SESSION["salesAID"] = $_POST["salesAID"];
			   }
			}
			else {
			   // if the variable is set in _POST but not in _SESSION, set it in _SESSION
			   $_SESSION["salesAID"] = $_POST["salesAID"];
			}
		 }

		 if(isset($_POST["custSelect"])) {    // check to avoid 'Notice: undefined index'
			if(isset($_SESSION["custSelect"])) {    // check to avoid 'Notice: undefined index'
			   if($_POST["custSelect"] != $_SESSION["custSelect"]) { 
				  // if the variable is set in both but _SESSION differs, update _SESSION
				  $_SESSION["custSelect"] = $_POST["custSelect"];
			   }
			}
			else {
			   // if the variable is set in _POST but not in _SESSION, set it in _SESSION
			   $_SESSION["custSelect"] = $_POST["custSelect"];
			}
		 }
    if(isset($_POST['create_quote'])){
        $query = "INSERT INTO Quote (salesAID, customerID) VALUES(${_SESSION['salesAID']}, ${_SESSION['custSelect']});";
		$salesquery = $pdoQuoteDB->query($query);
	}
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
		echo "  <input type='text' name='EmailText' value='${quoteRow['cusContact']}'/>";
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
		echo "<form action='finalizequote.php' method='POST'>\n";
		echo "  <input type='hidden' name='quoteID' value=$quoteID/>\n";
		echo "  <input type='submit' id='sanctionQuote' value='Finalize Quote'>\n";

		echo "</form>\n";

		echo "</html>\n";
	
}
catch (PDOexception $e) {
	echo "Connection to database failed: " . $e->getMessage();
}

?>