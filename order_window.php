<!DOCTYPE html>

<html>

<!--
    Wesley Kwiecinski - Z1896564, Group 2B
    Quote System
    CSCI 467

    This is the order confirmation page.
    The user can review the sanctioned quote, apply a final
    discount, and submit to the purchase order processing system.
-->


<head><title>Plant Repair Services</title></head>
<div class="header">
    <h1>Purchase Order Form</h1>
</div>

<style>
.header {
    text-align: center;
    background-color: #77b3de;
}

h2 {
    color: #77b3de;
}
</style>

<?php
//Reusable functions (drawing tables, loging into database, etc)
include("./common_functions.php");

//Gets information from given query with a pdo object
function get_information($query, $pdo)
{
    $rs = $pdo->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $rs->execute();
    return ($rs->fetchAll(PDO::FETCH_ASSOC));
}

//Log into the local database
//TODO: Update to use centralized database
$pdo = login_to_database("courses", "z1896564", "z1896564", "2000Aug17");
//Log into legacy database - this is for customer information
//need to grab customer information
$legacy = login_to_database("blitz.cs.niu.edu","csci467","student","student");

//Get all quote information from database
$quote_info = get_information("SELECT * FROM Quote WHERE quoteID={$_GET["quoteID"]};",$pdo);
//Get line item information
$line_items = get_information("SELECT * FROM LineItem WHERE quoteID={$_GET["quoteID"]};",$pdo);
//Get customer information
$customer_info = get_information("SELECT * FROM customers WHERE id={$quote_info[0]["customerID"]};",$legacy);

//Outline customer information in new form
//TODO: Format phone number
echo "<h2>Quote for " . $customer_info[0]["name"] . "</h2></br>";
echo $customer_info[0]["city"] . "</br>";
echo $customer_info[0]["street"] . "</br>";
$number = $customer_info[0]["contact"];
echo "<div id=\"phone\">$number</div>";
echo "</br>";

//Make table of line items
echo "<h3><b>Line Items:</b></h3></br>";
echo "<table cellspacing=5 border=1>";
$cost = 0;
foreach($line_items as $item)
{
    echo "<tr>";
    echo "<td>";
    echo $item["description"];
    echo "</td>";
    echo "<td>";
    echo $item["lineID"];
    echo "</td>";
    echo "</tr>";
    $cost += $item["price"];
}
echo "</table></br>";
//close table

//Make secret notes textbox
echo "<h3><b>Secret Notes</b></h3>";
echo "<textarea id=\"notes\" rows=\"5\" cols=\"50\" readonly>";
echo $quote_info[0]["sNotes"];
echo "</textarea></br>";
//end secret notes

//Discount box
//add button for percent and amount
echo "Discount: <input type=\"number\" id=\"discount_value\" step=\"0.01\" min=\"0\" value=0.00/>";
echo "<button onclick=\"calculateTotal()\" >Apply</button></br>";
echo "<input type=\"radio\" id=\"discount\" name=\"discount_type\" value=\"0\" checked/> percent </br>";
echo "<input type=\"radio\" id=\"discount\" name=\"discount_type\" value=\"1\" /> amount </br>";
echo "<p>Total: \$<div id=\"cost\">$cost</div></p>";
//Calculate total amount w/ Javascript?

//Get quote information and submit to the purchase order processing system

?>

<script>
//Calculate the total price when a discount is applied.
//Percent -> 0
//Amoutn -> 1
function calculateTotal()
{
    text = Number(document.getElementById('cost').textContent); //get cost text
    discount_type = document.querySelector('input[name="discount_type"]:checked');  //get radio buttons
    if(discount_type != null) discount_type = discount_type.value;  //get button value
    discount_value = Number(document.getElementById('discount_value').value);   //get the discount amount from input
    if(!isNaN(discount_type) && !isNaN(text) && !isNaN(discount_value)) //check they're all numbers
    {
        if(discount_type == 0)  //percentage based discount
        {
            text = text - (text * discount_value);  //apply discount
            if(text < 0) text = 0;  //clamp to zero
            text = Number.parseFloat(text).toFixed(2);  //round to 2 decimal places
            document.getElementById('cost').textContent = text; //place text back in document
        } else //amount based discount
        {
            text = text - discount_value;   //apply discount
            if(text < 0) text = 0;  //clamp to 0
            text = Number.parseFloat(text).toFixed(2);  //rounding
            document.getElementById('cost').textContent = text; //place value back in document
        }
    }
}

//Formats the phone number given from the sql query
function formatPhoneNumber(number)
{
    var text = document.getElementById('phone').textContent;    //get phone number
    if(!isNaN(text))    //check if number
        text = text.substring(0, 3) + "-" + text.substring(2, 5) + "-" + text.substring(4, 8);  //formatting
    document.getElementById('phone').textContent = text;    //place text in document
}

window.onload = formatPhoneNumber;  //format when page loads
</script>

</html>
