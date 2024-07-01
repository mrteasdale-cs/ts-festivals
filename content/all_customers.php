<?php
// import all files
global $conn;
require_once __DIR__ . '/../assets/templates/db_conn.php';
require_once __DIR__ . '/../assets/templates/template_functions.php';

echo makeHeader("T's Festivals");
echo makeNavMenu();

// Set variables
$pageTitle = "Admin Dashboard - All Customers";
$eventMsg = "";

if (!isset($_SESSION["isAdmin"]) || $_SESSION["isAdmin"] !== 1) {
    header("location: dashboard.php");
}

// Handle customer deletion
if (isset($_POST["deleteCust"])) {
    // Delete customer from the database

    $customerID = $_GET['customerID'];

    $deleteQuery = "DELETE FROM customers WHERE customerID = '$customerID'";

    if (mysqli_query($conn, $deleteQuery)) {

        $eventMsg = "<h3><span class='success'>Customer deleted successfully!</span></h3>";

    } else {
        $eventMsg = "<h3><span class='success'>Error deleting customer: " . mysqli_error($conn) . "</span></h3>";

    }
}

?>
    <main>
        <div id="secondary-banner">
            <?php echo makeSearchBar();?>
        </div>
        <div id="content">

            <div id="dashboard-content">

                <h1><?php echo $pageTitle ?></h1>

                <p>What would you like to do?</p>
                <!-- Create the dashboard menu from template -->
                <?php echo makeUserDashButtons(); ?>

                <br/><br/><br/>

                <script>

                </script>

                <?php

                    if (isset($eventMsg)) {
                        echo $eventMsg;
                    }
                    // Script to generate all customers

                    // This is the php script to retrieve customer information
                    $sqlQuery = "SELECT * FROM customers";
                    $getDetails = mysqli_query($conn, $sqlQuery);
                    $rows = mysqli_fetch_row($getDetails);

                    if ($rows > 0) {

                        echo "<table style='width: auto; margin: 0 auto; text-align: center;'>
                              <tr>
                                <th>CID</th>
                                <th>Forename</th>
                                <th>Surname</th>
                                <th>Email</th>
                                <th>Date of Birth</th>
                                <th>Admin?</th>
                              </tr>";

                        while ($row = mysqli_fetch_assoc($getDetails)) {

                            $customerID = $row['customerID'];
                            $isAdmin = "";
                            if ($row['isAdmin'] === '1'){
                                $isAdmin = "Yes (DO NOT REMOVE)";
                            } else {
                                $isAdmin = "No";
                            }

                            echo "<tr>
                                <td>{$row['customerID']}</td>
                                <td>{$row['customer_forename']}</td>
                                <td>{$row['customer_surname']}</td>
                                <td>{$row['customer_email']}</td>
                                <td>{$row['date_of_birth']}</td>  
                                <td>$isAdmin</td>                           
                                <td>
                                    <form action=\"./all_customers.php?customerID=$customerID\" method=\"POST\" onsubmit=\"return confirmAction('delete this customer');\"'>
                                      <button class=\"secondary-button\" name=\"deleteCust\" type=\"submit\">Delete</button>
                                    </form>
                                 </td>
                              </tr>";
                        }

                        echo "</table>";
                    } else {
                        echo "No customers found.";
                    }

                ?>

            </div>
        </div>
    </main>


<?php
echo makeFooter();