<!--
    Wesley Kwiecinski - Z1896564, Group 2B
    Quote System
    CSCI 467

    This is the ordering system for the quote system.
    This interface selects a quote, applies an optional final 
    discount, computers the final price, then sends the purchase order
    to the purchase order processing system
    It grabs the response and updates the database.
-->

<html>
    <head><title>Plant Repair Services</title></head>
    <div class="header">
        <h1>Ordering System</h1>
    </div>

<style>
.header {
    text-align: center;
    background-color: #77b3de;
}

.results {
    
}
</style>

<?php
//Reusable functions (drawing tables, loging into database, etc)
include("./common_functions.php");

//Log into the local database
//TODO: Update to use centralized database
$pdo = login_to_database("courses", "z1896564", "z1896564", "2000Aug17");
//Log into legacy database - this is for customer information
//need to grab customer name
$legacy = login_to_database("blitz.cs.niu.edu","csci467","student","student");

//Create a query that gets all quotes that have status value 2 "sanctioned"
//Dont need to prepare since we don't take in user input
$sql_query = "SELECT quoteID, procDateTime, name, commission, customerID FROM Quote,SalesAssoc WHERE status=2 AND Quote.salesAID=SalesAssoc.salesAID GROUP BY quoteID ORDER BY quoteID;";
$rs = $pdo->prepare($sql_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
$rs->execute();
$rows = $rs->fetchAll(PDO::FETCH_ASSOC);

//As long as rows exist
if(!empty($rows))
{
    //For result, we need quote id, date, sales assoc, customer name, price
    echo "Sanctioned Quotes";
    echo "<div class=\"results\">"; //for styling
    $count = 0;
    echo "<table cellspacing=1 border=1 >"; //table definition
    foreach($rows as $row)
    {
        echo "<form action=\"http://students.cs.niu.edu/~z1896564/Project2B_467_Ordering_System/order_window.php\" method=\"GET\" >";
        $qid = $row["quoteID"];
        //get customer info from legacy database based on customerID in quote
        $legacy_query = "SELECT name, contact FROM customers WHERE id={$row["customerID"]};";
        $rs = $legacy->prepare($legacy_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $rs->execute();
        $results = $rs->fetchAll(PDO::FETCH_ASSOC);
        //Need to get the customer name from the legacy database
        echo "<tr>";
        echo "<td>" . $row["quoteID"] . " (" . $row["procDateTime"] . "): " . $row["name"] . " - " . $results[0]["name"] . "</td>";
        echo "<td>" . "$" . $row["commission"] .  "</td>";
        echo "<td>" . "<input type=\"submit\" value=\"Send Purchase Order\" method=\"GET\" action=\"order_window.php\" />";
        echo "</tr>";
        echo "<input type=\"hidden\" name=\"quoteID\" value=$qid />";
        echo "</br>";
        echo "</form>";
        $count++;   //number of sanctioned quotes
    }
    echo "</table>";
    echo "</div>";
    echo "There are <b>" . $count . "</b> sanctioned quotes.";  //output total number of quotes
} else 
{
    echo "There are no sanctioned quotes to display.";
}

?>

<script>

</script>

</html>
