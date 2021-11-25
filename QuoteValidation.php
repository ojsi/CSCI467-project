<?php
include("credentials.php");	// Store your $username and $password in this file
include("dbLoginFunc.php");
include("login.php")
?>

<!DOCTYPE html>
<html>

<head>
    <title>Quote Confirmation</title>
</head>

<body>
    <!-- Title Screen for the page -->
    <div>
        <h1>Quote Submition</h1>
    </div>
    <!-- Return to the Quote Creation Page -->
    <div class="p-1 btn-group d-flex">
        <a href="quotegeneration.php" class="btn btn-success" target="_self">Back To Quote Creation</a>
    </div>

    <!-- Quote Validation -->
    <?php

    $pdoQuoteDB = loginToDatabase("courses", $username, $username, $password);

    // connect to external customer info legacy database
    $pdoCustDB = loginToDatabase("blitz.cs.niu.edu", "csci467", "student", "student");

    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        $data = htmlentities($data);
        return $data;
    }

    $lineitem = $_POST["lineitem"];
    $price = $_POST["price"];

    //Possible Error Messages
    $quotevalidation = "<br>";
    $valid = true;

    //Testing if the data is correct and not empty
    if (empty($_POST["name"])) {
        $quotevalidation = $quotevalidation . "Customer Name Information is missing<br>";
        $valid = false;
    } else {
        $name = test_input($_POST["name"]);
    }
    if (empty($_POST["contact"])) {
        $quotevalidation = $quotevalidation . "Customer Contact Information is missing<br>";
        $valid = false;
    } else {
        $contact = test_input($_POST["contact"]);
    }
    if (empty($_POST["email"])) {
        $quotevalidation = $quotevalidation . "Email is missing<br>";
        $valid = false;
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $quotevalidation = $quotevalidation . "Invalid email format, please format correctly<br>";
            $valid = false;
        }
    }
    if (empty($_POST["street"])) {
        $quotevalidation = $quotevalidation . "Customer Street Information is missing<br>";
        $valid = false;
    } else {
        $street = test_input($_POST["street"]);
    }
    if (empty($_POST["city"])) {
        $quotevalidation = $quotevalidation . "Customer City Information is missing<br>";
        $valid = false;
    } else {
        $city = test_input($_POST["city"]);
    }

    if (empty($_POST["message"])) {
        $message = "";
    } 
    else 
    {
        $message = test_input($_POST["message"]);
    }

    $Line_Price = array_combine($lineitem, $price);

    if ($valid) 
    {
        try {
            //Find a user from the session id.
            $salesassociate = $_SESSION['user_id'];
            $SA = $devPdo->prepare("SELECT * FROM sales_associates WHERE name = :user");
            $SA->bindParam(':user', $salesassociate);
            $SA->execute();
            $found = $SA->fetch();
            $nullable = 0;

            $insertQuote = $devPdo->prepare("INSERT INTO quotes (customer_name, contact, street, city, email ,secret_notes, status, discount, date_created, sales_associate_id)
        VALUES (:name, :contact, :street, :city, :email, :message, :status, :discount, CURDATE(), :salesassociate)");
            $insertQuote->bindParam(':name', $name);
            $insertQuote->bindParam(':contact', $contact);
            $insertQuote->bindParam(':street', $street);
            $insertQuote->bindParam(':city', $city);
            $insertQuote->bindParam(':email', $email);
            $insertQuote->bindParam(':message', $message);
            $insertQuote->bindParam(':status', $nullable);
            $insertQuote->bindParam(':discount', $nullable);
            $insertQuote->bindParam(':salesassociate', $found[0]);
            $insertQuote->execute();
            $last_id = $devPdo->lastInsertId();

            //Insert Lines with Prices into database
            $LineNumber = 1;
            foreach ($Line_Price as $Line => $Price) {
                $insertLine = $devPdo->prepare("INSERT INTO line_item (line_number, description, price, quote_id)
                    VALUES (:linenumber, :line, :price, :id)");
                $insertLine->bindParam(':linenumber', $LineNumber);
                $insertLine->bindParam(':line', $Line);
                $insertLine->bindParam(':price', $Price);
                $insertLine->bindParam(':id', $last_id);
                $insertLine->execute();
                $LineNumber++;
            }
            //If all was inserted correctly show success and quote number
            echo '<div style="text-align:center" class="alert alert-success">';
            echo '<strong>Success!</strong> New Quote Created #' . $last_id;
            echo '</div>';
        } catch (PDOException $e) {
            //if there was a pdo exception print it
            echo '<div style="text-align:center" class="alert alert-danger">';
            echo "MYSQL Error:<br>" . $e->getMessage();
            echo '</div>';
        }
        $conn = null;
    } else {
        //if any of the data was invalid.
        echo '<div style="text-align:center" class="alert alert-danger">';
        echo '<strong>Quote Creation Was Unsuccessful. Read Issues Below: </strong>' . $quotevalidation;
        echo '</div>';
    }
    ?>
</body>

</html>
