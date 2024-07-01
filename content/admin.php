<?php
// import all files
global $conn;
require_once __DIR__ . '/../assets/templates/db_conn.php';
require_once __DIR__ . '/../assets/templates/template_functions.php';


echo makeHeader("T's Festivals");
echo makeNavMenu();

// Set variables
$pageTitle = "Admin Dashboard - Home";

if (!isset($_SESSION["isAdmin"]) || $_SESSION["isAdmin"] !== 1) {
    header("location: dashboard.php");
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

                <h2>All Customers</h2>

                <?php // Script to generate all customers

                // This is the php script to retrieve customer information
                $sqlQuery = "SELECT * FROM customers";
                $getDetails = mysqli_query($conn, $sqlQuery);
                $rows = mysqli_fetch_row($getDetails);

                if ($rows > 0) {

                    echo "<table style='margin: 0 auto; text-align: center;'>
                          <tr>
                            <th>Customer ID</th>
                            <th>Forename</th>
                            <th>Surname</th>
                            <th>Email</th>
                            <th>Date of Birth</th>
                          </tr>";

                    while ($row = mysqli_fetch_assoc($getDetails)) {
                        echo "<tr>
                            <td>{$row['customerID']}</td>
                            <td>{$row['customer_forename']}</td>
                            <td>{$row['customer_surname']}</td>
                            <td>{$row['customer_email']}</td>
                            <td>{$row['date_of_birth']}</td>
                          </tr>";
                    }

                    echo "</table>";
                } else {
                    echo "No customers found.";
                }

                ?>

                <h2>All Events</h2>

                <?php // Script to generate all events

                // This is the php script to retrieve event information
                $sqlQuery = "SELECT * FROM events";
                $getDetails = mysqli_query($conn, $sqlQuery);

                if (mysqli_num_rows($getDetails) > 0) {

                    echo "<table style='margin: 0 auto; text-align: center;'>
                          <tr>
                            <th>Event ID</th>
                            <th>Title</th>
                            <th>Location</th>
                            <th>Date</th>
                          </tr>";

                    while ($row = mysqli_fetch_assoc($getDetails)) {
                        echo "<tr>
                            <td>{$row['eventID']}</td>
                            <td>{$row['event_title']}</td>
                            <td>{$row['event_location']}</td>
                            <td>{$row['event_date']}</td>
                          </tr>";
                    }

                    echo "</table>";
                } else {
                    echo "No events found.";
                }

                ?>

            </div>
        </div>
    </main>


<?php
echo makeFooter();