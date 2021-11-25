<?php
include("credentials.php");	// Store your $username and $password in this file
include("dbLoginFunc.php");
include("login.php")
?>
<!DOCTYPE html>
<html>
<!--Validate Input Function-->
<?php

$pdoQuoteDB = loginToDatabase("courses", $username, $username, $password);

// connect to external customer info legacy database
$pdoCustDB = loginToDatabase("blitz.cs.niu.edu", "csci467", "student", "student");    
$legacyPDO = login_to_database(args);
?>

<?php 
    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
//Variables for the customer information
$id = $name = $city = $street = $contact = "";
//If Post request from customer list then Autofill Information
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST["selectCust"] != "") {
        $customerSelection = test_input($_POST["selectCust"]);
        //MySQL Query for retrieving customer data from database
        $sql = "SELECT * FROM customers WHERE name='$customerSelection'";
        $customer = $legacyPDO->query($sql);
        $info = $customer->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($info)) {
            $name = $info[0]["name"];
            $street = $info[0]["street"];
            $city = $info[0]["city"];
            $contact = $info[0]["contact"];
        }
    }
}
?>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Quote Submition</title>
</head>
<body>
  </div>
    <!-- Title Of Page -->
        <h1>Quote Tracking</h1>
        <h5>Create a new Quote for your Customer</h5>
    </div>  
    <div id="CustomerSelection">
        <div class="p-1 btn-group d-flex">
            <a href="CustomerAccess.php" class="btn btn-primary" role="button" id="CustomerSelect" target="_self">Select Customer Info For Quote</a>
        </div>
    </div>
    <form id="QuoteForm" action="QuoteValidation.php" class="border border-primary rounded mx-1 px-2" method="post">
        <fieldset>
            <h4 class=".bg-info">Create New Quote</h4>
            <!-- Customer Information form group, All of the data is imported from Legacy database -->
            <div class="form-group">
                <div class="row">
                    <div class="col">
                        <label for="name">Customer Name</label>
                        <input onkeydown="event.preventDefault()" class="form-control" type="text" value="<?php echo $name ?>" name="name" id="name" placeholder="Customer Name" required><br>
                    </div>
                    </div>
                    <div class="col">
                        <label for="contact">Contact</label>
                        <input onkeydown="event.preventDefault()" class="form-control" type="text" value="<?php echo $contact ?>" name="contact" id="contact" placeholder="Contact"><br>
                    </div>
                 </div>
                    <div class="row">
                    <div class="col">
                        <label for="email">Email</label>
                        <input required class="form-control" type="text" name="email" placeholder="Email">
                    </div>
                <div class="row">
                    <div class="col">
                        <label for="street">Street</label>
                        <input onkeydown="event.preventDefault()" class="form-control" type="text" value="<?php echo $street ?>" name="street" id="street" placeholder="Street"><br>
                    </div>
                    <div class="col">
                        <label for="city">City</label>
                        <input onkeydown="event.preventDefault()" class="form-control" type="text" value="<?php echo $city ?>" name="city" id="city" placeholder="City"><br>
                    </div>
                </div>
            </div>

            <div id="QuoteContent">
                <div class="btn-group d-flex">
                    <button type="button" id="addField" class="btn btn-primary m-1">Add New Field+</button>
                </div>
            </div>

            <!-- Contains any secret Notes for the Quote -->
            <textarea name="message" class="form-control mt-2" placeholder="Secret Notes" rows="5"></textarea><br>
            <div class="btn-group d-flex">
                <button class="btn btn-success m-1" type="submit">Submit Quote</button>
            </div>

        </fieldset>
    </form>
    <!-- Scripts used for the addition of line items -->
    <script type="text/javascript" src="javascript/quotegen.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/es6-shim/0.35.3/es6-shim.min.js"></script>
</body>

</html>