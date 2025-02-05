<!DOCTYPE html>
    <!--
    Ojas DhiYogi - Z1849680, Group 2B
    Quote System
    CSCI 467
    Used by the sales associate after login to select the customer
    from the legacy database for the creation of the quote.
    -->
    <!-- Proccess Search for Customer Name-->
    <?php
    echo '<html>

    <body>
        <!-- Title of Page -->
        <div style="text-align:center" class="jumbotron jumbotron-fluid p-2 m-1 bg-info text-white rounded">
            <h1>Customer Information List</h1>
            <p>Select a Customer to use for the Quote</p>
        </div>
        <!-- Return to quote tracking button -->
        <div class="p-1 btn-group d-flex">
            <a href="logout.php" class="btn btn-danger" role="button" target="_self">Logout</a>
        </div>
    
        <!-- Search the customer list for a customer name -->
        <form action="CustomerAccess.php" class=" m-2 p-2" method="post">
            <div class="form-row input-group">
                <input type="text" class="form-control" placeholder="Search Customers" name="search">
                <button class="btn btn-primary" type="submit" class="">Search</button>
                <input type="hidden" name="salesAID" value="' . $_POST["salesAID"] . '">
    
            </div>
        </form>';
    include("credentials.php");	// Store your $username and $password in this file
    include("dbLoginFunc.php");
    //$legacyPDO = loginToDatabase("courses", $username, $username, $password);
    $legacyPDO = loginToDatabase("blitz.cs.niu.edu", "csci467", "student", "student");  
    if (isset($_POST["search"])) {
        $searchString = $_POST["search"];
        //Search for any paremeter such as the name, id, city, street or contact
        $customerSearch = $legacyPDO->prepare(
        "SELECT name, contact, id FROM customers 
        WHERE (name LIKE CONCAT('%', :string, '%'))
        OR (id LIKE CONCAT('%', :string, '%'))
        OR (city LIKE CONCAT('%', :string, '%'))
        OR (street LIKE CONCAT('%', :string, '%'))
        OR (contact LIKE CONCAT('%', :string, '%'))"
        );
        $customerSearch->execute(array(':string' => $searchString));
    } else {
        $customerSearch = $legacyPDO->prepare("SELECT name, contact, id FROM customers");
        $customerSearch->execute();
    }
    //Display all results on the table
    $rows = $customerSearch->fetchAll(PDO::FETCH_ASSOC);
    if (empty($rows)) {
        echo "None.";
     } else {
        // output data of each row
        echo "<table>";
        echo "<table border='0' cellpadding='5'>";
        echo "<form method='POST' action='newerquotegen.php'>";
        echo "' <input type='hidden' name='salesAID' value='${_POST['salesAID']}'>";
        foreach($rows as $rows) {
            echo "  <tr>\n";
            echo "    <td>${rows['contact']}</td>";
            echo "    <td>${rows['name']}</td>";
            echo "      <td><input type='radio' name='custSelect' value='${rows['id']}'/></td>";
            echo "</tr>";
        }
        echo "    <input type='submit' value='Create Quote' id='create_quote' name='create_quote'/>";
        echo "  </form>";
        echo "</table>";
    }
  
    ?>

</body>

<!-- JavaScript for selection of the customer-->
<script>
     var previousrow;

    function addRowHandlers() {
        //Get rows in table
        var table = document.getElementsByClassName("table");
        var rows = table[0].getElementsByTagName("tr");
        for (i = 0; i < rows.length; i++) {
            var currentRow = table[0].rows[i];
            var createClickHandler =
                function(row) {
                    return function() {
                        if (previousrow != undefined) {
                            previousrow.setAttribute("style", "");
                        }
                        var cell = row.getElementsByTagName("td")[0];
                        var selection = cell.innerHTML;
                        document.getElementById("selectCust").setAttribute("value", selection);
                        this.setAttribute("style", "background-color: #e8e8e8; color: #000000 ");
                        previousrow = row;
                    };
                };
            currentRow.onclick = createClickHandler(currentRow);
        }
    }
    window.onload = addRowHandlers();
</script>

</html>