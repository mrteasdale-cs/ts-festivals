<?php
// Dashboard File for User Account
// import all files
global $conn;
require_once __DIR__ . '/../assets/templates/db_conn.php';
require_once __DIR__ . '/../assets/templates/template_functions.php';

echo makeHeader("T's Festivals");
echo makeNavMenu();

// Initialise a session
// session_start();

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] !== true){
        header("location: login.php");
        exit;
}

// Initialise and assign variables
$pageTitle = "Dashboard";
$customerID = htmlspecialchars($_SESSION['customer_Id']);
$customer_forename = $customer_surname = $customer_email = $dateRemoveTime = $passw = $conf_pass = "";
$generic_error = $email_err = $pass_err = $conf_pass_err = $details_err = $fname_err = $sname_err = $dob_err = $successString ="";

// This is the php script to retrieve customer information
$sqlQuery = "SELECT customer_forename, customer_surname, customer_email, date_of_birth FROM customers where customerId='$customerID'";   //create query to retrieve all details from cust table
$getDetails = mysqli_query($conn, $sqlQuery);

if (mysqli_num_rows($getDetails) > 0) {

    while ($row = $getDetails->fetch_assoc()) //a while loop, this will display each of the variables inside the brackets for how many records of each they are
    {
        $customer_forename = $row['customer_forename'];
        $customer_surname = $row['customer_surname'];
        $customer_email = $row['customer_email'];
        $date_of_birth = $row['date_of_birth'];
        //prettify the date
        $dateRemoveTime = substr($date_of_birth, 0, 10);
    }
}

// Script to update the customers information via the form on the dashboard
if($_SERVER["REQUEST_METHOD"] == "POST") {

    // Fetch new data from the form the user has updated their details in
    $customer_forename = $_POST['customer_forename'];
    $customer_surname = $_POST['customer_surname'];
    $customer_email = $_POST['customer_email'];
    $date_of_birth = $_POST['date_of_birth'];
    // Check if any field is empty
    if(empty($customer_forename) || empty($customer_surname) || empty($customer_email) || empty($date_of_birth)) {
        $generic_error = "All fields must be filled out.";
    } else {
        // Draft the SQL statement
        $sqlQuery = "UPDATE customers SET customer_forename = ?, customer_surname = ?, customer_email = ?, date_of_birth = ?
                            WHERE customerID = ?";

        // Prepare SQL statement
        if ($stmt = mysqli_prepare($conn, $sqlQuery)) {
            // Bind variables
            //$customer_email = filter_var($customer_email, FILTER_SANITIZE_EMAIL);
            mysqli_stmt_bind_param($stmt, "ssssi", $customer_forename, $customer_surname, $customer_email, $date_of_birth, $customerID);

            // Returns boolean, if true then execute query
            if (mysqli_stmt_execute($stmt)) {

                $successString = "Record successfully updated.";

            } else {
                $successString = "Customer ID $customerID Update failed.";
            }
        } else {
            echo "Error in prepare statement.";
        }
    }
}
?>
        <main>
            <div id="secondary-banner">
                <?php echo makeSearchBar();?>
            </div>
            <div id="content">

                <div id="dashboard-content">

                    <h1><?php echo $customer_forename ?>'s <?php echo $pageTitle ?></h1>

                    <p>What would you like to do?</p>
                    <!-- Create the dashboard menu from template -->
                    <?php echo makeUserDashButtons(); ?>

                    <section class="flex-parent-section">

                        <section class="flex-child">

                        <h2>My Bookings</h2>

                        <p>Here are your current bookings:</p>

                            <?php // Script to generate current bookings

                            // This is the php script to retrieve booking information
                            $sqlQuery = "select events.eventID, events.event_title, events.event_location, events.event_date,
                                                booking.eventId, booking.number_people, booking.total_booking_cost, booking.booking_notes
                                                FROM booking
                                                INNER JOIN events
                                                    ON booking.eventID = events.eventID
                                                WHERE customerId='$customerID'";
                            $getDetails = mysqli_query($conn, $sqlQuery);

                            $rows = mysqli_num_rows($getDetails);

                            if ($rows > 0) {

                                echo "<table class=\"\" style='margin: 0 auto; text-align: center;'>";
                                $eventNum = 1;

                                while ($row = mysqli_fetch_assoc($getDetails)) {
                                    //a while loop, this will display each of the variables inside the brackets for how
                                    // many records of each they are
                                    $eventID = $row['eventId'];
                                    $eventName = $row['event_title'];
                                    $eventLocation = $row['event_location'];
                                    $numberPeople = $row['number_people'];
                                    $totalBookingCost = $row['total_booking_cost'];
                                    $bookingNotes = $row['booking_notes'];
                                    $eventDate = $row['event_date'];

                                    //Remove the 00-00-00 time from date to format into input box
                                    $dateRemoveTime = substr($eventDate, 0, 10);

                                    echo "
                                                  <th><h3>Event $eventNum</h3></th> 
                                                  <tr>
                                                    <td><span class='highlight'>Event Details:</span> $eventName, $eventLocation</td>
                                                  </tr> 
                                                  <tr>
                                                    <td>$numberPeople ticket(s) purchased</td>
                                                  </tr> 
                                                  <tr>
                                                    <td><span class='highlight'>Date:</span> $dateRemoveTime
                                                  </tr> 
                                                  <tr>
                                                    <td><span class='highlight'>Total Cost:</span> £$totalBookingCost</td>
                                                  </tr> 
                                                  <tr>
                                                    <td><span class='highlight'>Additional notes:</span> $bookingNotes</td>
                                                  </tr>
                                                  <tr>
                                                  <td>
                                                      <form action=\"../booking_cancel.php?eventID=$eventID&custID=$customerID\" method=\"POST\">
                                                            <button class=\"primary-button\" type=\"submit\">Cancel</button>
                                                      </form>
                                                  </tr>
                                                ";
                                    $eventNum += 1;
                                }


                                echo "    </table><br /><br />";
                            } else  {
                                echo "<span class='important'>You do not have any bookings.</span>";
                            }
                            mysqli_close($conn);

                            ?>
                        </section>

                        <section class="flex-child">

                            <h2>My Details</h2>

                            <p>If your details are inaccurate, please update and click the update button below.</p>

                            <span class="success">
                                <?php
                                if (isset($successString)) {
                                    echo $successString;
                                }; ?>
                            </span>
                            <br /><br />

                            <!-- CUSTOMER DETAILS UPDATE FORM -->
                            <form class="input-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                                <div class="group">
                                    <label for="customer_forename">Firstname: </label>
                                    <input type="text" id="customer_forename" name="customer_forename" value="<?php echo $customer_forename; ?>" placeholder="Joe"> <!--pattern=[A-Z\sa-z]{3,20}-->
                                    <span class="error"><br /><?php echo $fname_err; ?></span>
                                </div>
                                <div class="group">
                                    <label for="customer_surname">Surname: </label>
                                    <input type="text" id="customer_surname" name="customer_surname" value="<?php echo $customer_surname; ?>" placeholder="Bloggs">
                                    <span class="error"><br /><?php echo $sname_err; ?></span>
                                </div>
                                <div class="group">
                                    <label for="customer_email">E-mail: </label>
                                    <input type="email" id="customer_email" name="customer_email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $customer_email; ?>" placeholder="jBloggs@email.com">
                                    <span class="error"><br /><?php echo $email_err; ?></span>
                                </div>
                                <div class="group">
                                    <label for="date_of_birth">Date of Birth</label>
                                    <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo $dateRemoveTime; ?>">
                                    <span class="error"><br /><?php echo $dob_err; ?></span>
                                </div>

                                <!-- show user genreic error if any blank fields -->
                                <span class="error"><br /><?php echo $generic_error; ?></span>

                                <div class="group">
                                    <button class="primary-button" type="submit">Update</button>
                                </div>
                            </form>

                        </section>

                    </section>

                </div>

                <!-- To top navigation button function -->
                <?php echo makeToTopNav(); ?>

        </main>

<?php
echo makeFooter();