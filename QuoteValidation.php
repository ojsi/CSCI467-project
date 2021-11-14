<!DOCTYPE html>
<html>

<head>
    <title>Quote Confirmation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
    <!-- Title Screen for the page -->
    <div>
        <h1>Quote Submition</h1>
    </div>
    <!-- Return to the Quote Creation Page -->
    <div class="p-1 btn-group d-flex">
        <a href="tracking.php" class="btn btn-success" target="_self">Back To Quote Creation</a>
    </div>

    <!-- Quote Validation -->
    <?php
    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        $data = htmlentities($data);
        return $data;
    }