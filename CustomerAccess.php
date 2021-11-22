<!DOCTYPE html>
<html>

<body>
    <!-- Title of Page -->
    <div style="text-align:center" class="jumbotron jumbotron-fluid p-2 m-1 bg-info text-white rounded">
        <h1>Customer Information List</h1>
        <p>Select a Customer to use for the Quote</p>
    </div>
    <!-- Return to quote tracking button -->
    <div class="p-1 btn-group d-flex">
        <a href="tracking.php" class="btn btn-danger" role="button" target="_self">Back To Quote</a>
    </div>

    <!-- Input to send customer name back to the quote for processing -->
    <form action="tracking.php" class="m-2 p-2" method="post">
        <div class="form-row input-group">
            <input onkeydown="event.preventDefault()" type="text" class="form-control" id="selectCust" name="selectCust" required placeholder="Click On Table To Select Customer">
            <input class="btn btn-success" type="submit">
        </div>
    </form>

    <!-- Search the customer list for a customer name -->
    <form action="CustomerList.php" class=" m-2 p-2" method="post">
        <div class="form-row input-group">
            <input type="text" class="form-control" placeholder="Search Customers" name="search">
            <button class="btn btn-primary" type="submit" class="">Search</button>
        </div>
    </form>

    <!-- Proccess Search for Customer Name-->
    <?php
    if (isset($_POST["search"])) {
        $searchString = $_POST["search"];
        //Search for any paremeter such as the name, id, city, street or contact
        $customerSearch = $legacyPDO->prepare(
            "SELECT name, contact FROM customers 
        WHERE (name LIKE CONCAT('%', :string, '%'))
        OR (id LIKE CONCAT('%', :string, '%'))
        OR (city LIKE CONCAT('%', :string, '%'))
        OR (street LIKE CONCAT('%', :string, '%'))
        OR (contact LIKE CONCAT('%', :string, '%'))"
        );
        $customerSearch->execute(array(':string' => $searchString));
    } else {
        $customerSearch = $legacyPDO->prepare("SELECT name, contact FROM customers");
        $customerSearch->execute();
    }
    //Display all results on the table
    $rows = $customerSearch->fetchAll(PDO::FETCH_ASSOC);
    echo '<div class="row m-2 p-2">', tableHead($rows), tableBody($rows), "</div>";
    ?>

</body>

<!-- JavaScript for selection of the customer-->
<script>
     var previousrow;

    function addRowHandlers() {
        //Get rows in table
        var table = document.getElementsByClassName("datatbl");
        var rows = table[0].getElementsByTagName("tr");
        for (i = 0; i < rows.length; i++) {
            var currentRow = table[0].rows[i];
            var createClickHandler =
                function(row) {
                    return function() 
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