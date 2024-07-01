<?php
// import all files
global $conn;
require_once __DIR__ . '/../assets/templates/db_conn.php';
require_once __DIR__ . '/../assets/templates/template_functions.php';

echo makeHeader("T's Festivals");
echo makeNavMenu();

// Set variables
$pageTitle = "Admin Dashboard - Events";
$eventMsg = $errors = $successString = "";
$eventID;

if (!isset($_SESSION["isAdmin"]) || $_SESSION["isAdmin"] !== 1) {
    header("location: dashboard.php");
}

// Handle deleting events
if (isset($_POST["deleteEvent"])) {
    // Delete event from the database
    $eventID = $_GET['eventID'];

    $deleteQuery = "DELETE FROM events WHERE eventID = '$eventID'";

    if (mysqli_query($conn, $deleteQuery)) {

        $eventMsg = "<h3><span class='success'>Event deleted successfully!</span></h3>";

    } else {
        $eventMsg = "<h3><span class='success'>Error deleting event: " . mysqli_error($conn) . "</span></h3>";

    }
}

// Handle editing the event
if (isset($_POST["editEventConfirm"])) {

    // Fetch new data from the form the user has updated their details in
    $eventID = $_POST['eventID'];
    $eventTitle = $_POST['event_title'];
    $eventLocation = $_POST['event_location'];
    $eventDate = $_POST['event_date'];
    $eventDesc = $_POST['description'];
    $eventPrice = $_POST['price_per_person'];
    $eventImagePath = $_POST['event_imagepath'];
    $promotion = $_POST['promotion'];
    $topPick = $_POST['top_pick'];

    // Check if any field is empty
    if(empty($eventTitle) || empty($eventLocation) || empty($eventDesc) || empty($eventDate) || empty($eventPrice)) {
        $errors = "All fields must be filled out.";
    } else {
        // Draft the SQL statement
        $sqlQuery = "UPDATE events SET event_title = ?, event_location = ?, description = ?, event_date = ?, 
                  price_per_person = ?, event_imagepath = ?, promotion = ?, top_pick = ?  WHERE eventID = '$eventID'";

        // Prepare SQL statement
        if ($stmt = mysqli_prepare($conn, $sqlQuery)) {
            // Bind variables
            //$customer_email = filter_var($customer_email, FILTER_SANITIZE_EMAIL);
            mysqli_stmt_bind_param($stmt, "ssssdsii", $eventTitle, $eventLocation, $eventDesc, $eventDate, $eventPrice, $eventImagePath, $promotion, $topPick);
            // Returns boolean, if true then execute query
            if (mysqli_stmt_execute($stmt)) {

                $successString = "<h3><span class='success'>Event record successfully updated.</span></h3>";

            } else {
                $successString = "<h3><span class='error'>Event ID $eventID Update failed." . mysqli_error($conn) . "</span></h3>";
            }
        } else {
            echo "Error in prepare statement.";
        }
    }
}



?>
    <main>
        <div id="secondary-banner">
            <!-- call the searchbar function -->
            <?php echo makeSearchBar();?>
        </div>
        <div id="content">

            <div id="dashboard-content">

                <h1><?php echo $pageTitle ?></h1>

                <p>What would you like to do?</p>
                <?php
                    // create user dashboard
                    echo makeUserDashButtons();

                    // echo any messages if form has been posted
                    if (isset($eventMsg)) {
                    echo $eventMsg;
                    }

                    // Handle displaying of event editing screen
                    if (isset($_POST["editEvent"]) || isset($_POST["editEventConfirm"])) {
                        // Delete event from the database
                        $eventID = $_GET['eventID'];
                        $sqlQuery = "SELECT * FROM events where eventID = '$eventID'";
                        $getDetails = mysqli_query($conn, $sqlQuery);

                        while ($row = mysqli_fetch_assoc($getDetails)) {
                            $eventID = $row['eventID'];
                            $eventTitle = $row['event_title'];
                            $eventLocation = $row['event_location'];
                            $eventDate = $row['event_date'];
                            $eventDesc = $row['description'];
                            $eventPrice = $row['price_per_person'];
                            $eventImagePath = $row['event_imagepath'];
                            $promotion = $row['promotion'];
                            $topPick = $row['top_pick'];
                        }

                        //Remove the 00-00-00 time from date to format into input box
                        $dateRemoveTime = substr($eventDate, 0, 10);

                        echo "  
                            <!-- Form to edit event -->                            
                            <h3>Update an Event (Complete all fields)</h3>
                            ";
                                if (isset($successString)) {
                                        echo $successString;
                                } elseif (isset($errors)) {
                                    echo $errors;
                                }

                        echo "      
                                    <form class=\"input-form\" action=\"all_events.php?eventID=$eventID\" method=\"post\" onsubmit=\"return confirmAction('edit this event');\">
                                    <label for=\"event_title\">Event Title:</label>
                                    <input type=\"text\" id=\"event_title\" name=\"event_title\" value=\"$eventTitle\">
                                    
                                    <label for=\"event_location\">Location:</label>
                                    <input type=\"text\" id=\"event_location\" name=\"event_location\" value=\"$eventLocation\">
                
                                    <label for=\"description\">Description:</label>
                                    <textarea id=\"description\" name=\"description\" rows=\"3\" maxlength=\"255\">$eventDesc</textarea>
                
                                    <label for=\"event_date\">Event Date:</label>
                                    <input type=\"date\" id=\"event_date\" name=\"event_date\" value=\"$dateRemoveTime\">
                
                                    <label for=\"price_per_person\">Price Per Person:</label>
                                    <input type=\"text\" id=\"price_per_person\" name=\"price_per_person\" value=\"$eventPrice\" min=\"0\" step=\"0.01\"  >
                
                                    <label for=\"event_imagepath\">Image Name:</label>
                                    <input type=\"text\" id=\"event_imagepath\" name=\"event_imagepath\" value=\"$eventImagePath\">
                                    
                                    <label for=\"promotion\">Promotion:</label>
                                    <select id=\"promotion\" name=\"promotion\">
                                        <option value=\"0\">No</option>
                                        <option value=\"1\">Yes</option>
                                    </select>
                
                                    <label for=\"top_pick\">Top Pick:</label>
                                    <select id=\"top_pick\" name=\"top_pick\">
                                        <option value=\"0\">No</option>
                                        <option value=\"1\">Yes</option>
                                    </select>
                                    
                                    <input name='eventID' type='hidden' value='$eventID'>
                               
                                    <div class=\"group\">
                                         <button class=\"primary-button\" name=\"editEventConfirm\" type=\"submit\">Update</button>
                                    </div>
                                </form>
                        ";
                    } else {

                        // Script to generate all events
                        // This is the php script to retrieve event information
                        $sqlQuery = "SELECT * FROM events";
                        $getDetails = mysqli_query($conn, $sqlQuery);

                        if (mysqli_num_rows($getDetails) > 0) {

                            echo "
                      
                            <h3>All Events</h3>
                              <table style='margin: 0 auto; text-align: center;'>
                                  <tr>
                                    <th>EID</th>
                                    <th>Title</th>
                                    <th>Location</th>
                                    <th>Date</th>
                                    <th>Image</th>
                                    <th>Action</th>
                                  </tr>";

                            while ($row = mysqli_fetch_assoc($getDetails)) {

                                $eventID = $row['eventID'];
                                $eventTitle = $row['event_title'];
                                $eventLocation = $row['event_location'];
                                $eventDate = $row['event_date'];
                                $eventDesc = $row['description'];
                                $eventImagePath = $row['event_imagepath'];

                                echo "<tr>
                                <td>$eventID</td>
                                <td>$eventTitle</td>
                                <td>$eventLocation</td>
                                <td>$eventDate</td>
                                <td>$eventImagePath</td>
                                <td> 
                                    
                                    <form action=\"./all_events.php?eventID=$eventID\" method=\"POST\" onsubmit=\"return confirmAction('perform that action');\">
                                      <button class=\"secondary-button\" name=\"editEvent\" type=\"submit\">Edit</button>
                                      <button class=\"secondary-button\" name=\"deleteEvent\" type=\"submit\">Delete</button>
                                    </form>
                                </td>
                              </tr>";
                            }

                            echo "</table>";
                        } else {
                            echo "<h3>No events found.</h3>";
                        }
                    }

                ?>
            </div>
        </div>
    </main>


<?php
echo makeFooter();