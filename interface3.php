<html>
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
include("./credentials.php"); //central database login information

try {

    //Log into the local database
    $pdo = login_to_database("courses", $username, $username, $password);
    //Log into legacy database - this is for customer information
    //need to grab customer name
    $legacy = login_to_database("blitz.cs.niu.edu","csci467","student","student");

    //Create a query that gets all quotes that have status value 2 "sanctioned"
    //Dont need to prepare since we don't take in user input
    $sql_query = "SELECT quoteID, procDateTime, name, customerID FROM Quote,SalesAssoc WHERE status=2 AND Quote.salesAID=SalesAssoc.salesAID GROUP BY quoteID ORDER BY quoteID;";
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
        echo "<table cellpadding=5 cellspacing=10 border=1 >"; //table definition
        foreach($rows as $row)
        {
            echo "<form action=\"./order_window.php\" method=\"GET\" >";
            $qid = $row["quoteID"];
            //get customer info from legacy database based on customerID in quote
            $legacy_query = "SELECT name, contact FROM customers WHERE id={$row["customerID"]};";
            $rs = $legacy->prepare($legacy_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $rs->execute();
            $results = $rs->fetchAll(PDO::FETCH_ASSOC);
            //Need to get the customer name from the legacy database
            echo "<tr>";    //table formatting
            echo "<td>" . $row["quoteID"] . " (" . $row["procDateTime"] . "): " . $row["name"] . " - " . $results[0]["name"] . "</td>";
            //echo "<td>" . "$" . $row["commission"] .  "</td>";    //remove commission since its unnecessary and usually inaccurate
            echo "<td>" . "<input type=\"submit\" value=\"Send Purchase Order\" method=\"GET\" action=\"./order_window.php\" />";
            echo "</tr>";
            echo "<input type=\"hidden\" name=\"quoteID\" value=$qid />";
            echo "</br>";
            echo "</form>";
            $count++;   //number of sanctioned quotes
        }
        echo "</table>";    //close table
        echo "</div>";
        echo "There are <b>" . $count . "</b> sanctioned quotes.";  //output total number of quotes
    } else 
    {
        echo "There are no sanctioned quotes to display.";
    }

}catch (PDOException $e)    //display error if can't connect
{
    echo "<p>Could not connect to database: " . $e->getMessage() . "</p>";  //print error if can't connect
}

?>

<form action="index.html"><input type=submit class=button name=return value="Back to Home Page"></form> </div>
</html>
