<html>
    <!--
    Wesley Kwiecinski - Z1896564, Group 2B
    Quote System
    CSCI 467

    This is the ordering system for the quote system.
    Takes quote information from the order window
    and sends it to the purchase order processing system.
    Then returns the user to the index page.
    -->

<?php

//need to send information to pops
//login to databases
//Reusable functions (drawing tables, loging into database, etc)
include("./common_functions.php");
include("./login.php");

//Log into the local database
$pdo = login_to_database("courses", $username, $username, $password);

//Pops url
$url = 'http://blitz.cs.niu.edu/PurchaseOrder/';
//Data array for sending to pops
$data = array(
	'order' => $_GET["orderid"],    //order id
	'associate' => $_GET["salesid"],//sales id
	'custid' => $_GET["custid"],    //customer id
	'amount' => $_GET["cost"]       //cost
);

//Options for pushing to pops - from sample programs in 
$options = array(
    'http' => array(
        'header' => array('Content-type: application/json', 'Accept: application/json'),
        'method'  => 'POST',
        'content' => json_encode($data)
    )
);

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context); //get the result from the pops
$res = json_decode($result);    //decode the result

if(json_last_error() === JSON_ERROR_NONE)
{
    $com = null;
    $update_query = null;
    //Update quote database and sales commission database
    if(isset($res->commission)) //update if commission value exists
        $com = floatval($res->amount) * (floatval($res->commission) * 0.01);
    if(isset($res->processDay) && isset($com))  //check if results actually exist
    {
        //Update quote database and sales associate database query
        $update_query = "UPDATE Quote SET status=3, procDateTime={$res->processDay}, commission=$com
            WHERE quoteID={$_GET["orderid"]};
            UPDATE SalesAssoc SET accumComm=accumComm+$com WHERE salesAID={$_GET["salesid"]};";

        $rs = $pdo->prepare($update_query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $rs->execute();
        echo "Order was processed.";
        echo "<script>alert(\"Email has been sent to $res->name\")</script>";
    }
} else 
{
    echo "An error occurred.";
}
?>

<script>
//return to the home page of the processing system
function returnToHome()
{
    window.location.assign("http://students.cs.niu.edu/~z1896564/Project2B_467_Ordering_System/index.php");
}

window.onload = returnToHome;  //when page loads return home
</script>

</html>