<?php
// Booking Cancel Script
// import all files
global $conn;
require_once __DIR__ . '/../assets/templates/db_conn.php';
require_once __DIR__ . '/../assets/templates/template_functions.php';

echo makeHeader("T's Festivals");
echo makeNavMenu();
// Initialise a session
session_start();

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true){
    header("location: login.php");
    exit;

}?>


<main>
    <div id="secondary-banner">
        <?php echo makeSearchBar();?>
    </div>
    <div id="content">

        <div id="dashboard-content">

            <h1>Success</h1>


            <?php
                // Validate booking
                if (empty(htmlspecialchars($_SESSION["customer_Id"]))){

                    echo "Error";

                } else {

                    // Assign variables to perform a SQL select
                    $eventID = $_GET["eventID"];
                    $customerId = htmlspecialchars($_SESSION["customer_Id"]);
                    // Prepare an SQL statement to get users emails
                    $selectIDQuery = "SELECT bookingID from booking WHERE eventID = ? AND customerID = ?";

                    if ($stmt = mysqli_prepare($conn, $selectIDQuery)) {

                        // Bind variables to the prepared statement as parameters
                        mysqli_stmt_bind_param($stmt, "ss", $eventID, $customerId);
                        // Attempt to execute the select statement
                        if (mysqli_stmt_execute($stmt)) {

                            $queryResult = mysqli_stmt_get_result($stmt);

                            $rows = mysqli_num_rows($queryResult);

                            // If number of rows is 1 and there is already a booking with the same
                            // cust id and event then prevent double booking.
                            if($rows == 0){

                                echo "<p><span class=\"error\">No booking found or booking already deleted.</span></p>";

                            } else {
                                while ($row = mysqli_fetch_assoc($queryResult)) {

                                    $bookingId = $row['bookingID'];
                                    // RUN THE SQL INSERT SCRIPT TO ADD NEW BOOKING IF BOOKING MADE FOR FIRST TIME
                                    $deleteBookingQuery = "DELETE FROM booking WHERE bookingID = ?";


                                    if ($stmt = mysqli_prepare($conn, $deleteBookingQuery)) {
                                        // Bind variables to the prepared statement as parameters
                                        mysqli_stmt_bind_param($stmt, "i", $bookingId);

                                        if (mysqli_stmt_execute($stmt)) {

                                            echo "  <p><span class='success'>Booking Cancelled. A Refund will be processed within 5-7 working days.</span></p> 
                                                    <p><span class='success'>Contact support@tsfestival.com if you still have not received anything by then.</span></p> 
                                                    <p><span class='success'>Taking you back to your dashboard</span></p>
                                                  ";
                                            header('refresh:6;url=dashboard.php'); // Redirect to the dashboard page again

                                        }
                                        // Close query
                                        mysqli_stmt_close($stmt);
                                    }
                                }
                            }
                        }
                    }
                }
                ?>
            </div>

        <!-- To top navigation button function -->
        <?php echo makeToTopNav(); ?>

    </main>

<?php
echo makeFooter();