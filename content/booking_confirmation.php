<?php
// Booking confirmation page
// import all files
global $conn;
require_once __DIR__ . '/../assets/templates/db_conn.php';
require_once __DIR__ . '/../assets/templates/template_functions.php';

echo makeHeader("T's Festivals");
echo makeNavMenu();
// Initialise a session
// session_start();
// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true){
    header("location: login.php");
    exit;
}

/* DUMMY CREDIT CARD FOR TESTING
Card number: 5110926550850756
Expiry date: 08/2028
CVC code: 628
*/

// Initialise variables
$pageTitle = "Booking Confirmation";
?>
    <main>
        <div id="secondary-banner">
            <?php echo makeSearchBar();?>
        </div>
        <div id="content">

            <h1><?php echo $pageTitle ?></h1>

                <?php

                // Validate booking

                if (empty(htmlspecialchars($_SESSION["customer_Id"]))){

                    echo "Error";

                } else {

                    // Assign variables to perform a SQL select
                    $eventID = $_GET["eventID"];
                    $customerId = htmlspecialchars($_SESSION["customer_Id"]);
                    // Prepare an SQL statement to get users emails
                    $sqlQuery = "SELECT bookingId from booking WHERE eventID = '$eventID' AND customerID = '$customerId'";

                    if ($stmt = mysqli_prepare($conn, $sqlQuery)) {

                        // Attempt to execute the select statement
                        if (mysqli_stmt_execute($stmt)) {

                            mysqli_stmt_store_result($stmt);
                            $rows = $stmt->num_rows;

                            // If number of rows is 1 and there is already a booking with the same
                            // cust id and event then prevent double booking.
                            if($rows > 0){

                                echo "<p><span class=\"success\">Booking already made. Sit back and relax. Redirecting...</span></p>";
                                header('refresh:5;url=dashboard.php'); // Redirect to the dashboard page again

                            } else {

                                // RUN THE SQL INSERT SCRIPT TO ADD NEW BOOKING IF BOOKING MADE FOR FIRST TIME
                                $sqlQuery = "INSERT INTO booking (eventId, customerID, number_people, total_booking_cost, booking_notes)
                                            VALUES (?,?,?,?,?)";

                                if ($stmt = mysqli_prepare($conn, $sqlQuery)) {

                                    // Assign values to variables
                                    $num_people = trim($_POST["num_people"]);
                                    $price = trim($_POST["price"]);
                                    $booking_notes = trim($_POST["booking_notes"]);
                                    $eventTitle = $_GET['eventTitle'];

                                    // Calculate total booking cost
                                    $total_booking_cost = floatval($num_people) * floatval($price);
                                    $total_booking_cost = round($total_booking_cost, 2);
                                    // Bind variables to the prepared statement as parameters
                                    mysqli_stmt_bind_param($stmt, "iiids", $eventID, $customerId, $num_people, $total_booking_cost, $booking_notes);

                                    // Execute the statement
                                    if (mysqli_stmt_execute($stmt)) {

                                        echo "  
                                              
                                              <h3>You are 'almost' on your way to $eventTitle!!!</h3>
                                              
                                                <table class='clear'>
                                                    <tr>
                                                        <th>Event</th>
                                                        <td>$eventTitle</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Your ID (keep this if you wish to contact us)</th>
                                                        <td>$customerId</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Number of tickets</th>
                                                        <td>$num_people</td>
                                                    </tr>
                                                    <tr id=\"total_cost_cell\">
                                                        <th>Total Cost</th>
                                                        <td>$total_booking_cost</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Notes</th>
                                                        <td>$booking_notes</td>
                                                    </tr>
                                                </table>
                                                                                                                   
                                                <p>
                                                    To secure your place now, please pay via PayPal or Debit/Credit Card. Tickets will be emailed upon recipt of payment.
                                                </p> 
                                                <p>
                                                    Ensure you bering ID with you as under 21's will be challenged.
                                                </p>
                                                <p>
                                                    <div id=\"paypal-button-container\"></div>
                                                </p>
                                                <script src=\"https://www.paypal.com/sdk/js?client-id=Acs_hT_mA8voMU8kd_QXRn-_rc02AkrPb-AaK7vksoh9DISri9wHeS7dKHF7ayFinYsgbD8ufLBakPIL&components=buttons&enable-funding=paylater,venmo,card\" data-sdk-integration-source=\"integrationbuilder_sc\"></script>
                                                <script src=\"./client/app.js\"></script>      

                                            ";
                                    }
                                    // Close
                                    mysqli_stmt_close($stmt);
                                }

                            }
                        }
                    }
                }
                ?>
            <br />
            <p>
                <h3>Navigation</h3>
                <button class="primary-button" type="submit" onclick="window.location.href = '../dashboard.php'">Dashboard</button>
                <button class="primary-button" type="submit" onclick="window.location.href = '../index.php'">Homepage</button>
                <button class="primary-button" type="submit" onclick="window.location.href = '../logout.php'">Logout</button>
            </p>
        </div>
    </main>
<?php
echo makeFooter();