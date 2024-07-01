<?php
// import all files
global $conn;
require_once __DIR__ . '/../assets/templates/db_conn.php';
require_once __DIR__ . '/../assets/templates/template_functions.php';

echo makeHeader("T's Festivals");
echo makeNavMenu();

// Set variables
$pageTitle = "Admin Dashboard - Add New Event";
$eventMsg  = "";

if (!isset($_SESSION["isAdmin"]) || $_SESSION["isAdmin"] !== 1) {
    header("location: dashboard.php");
}

// Script to add new event to DB
// Check if form submitted via php self POST
// Check if form submitted via php self POST
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data and validate
    $errors = [];
    // Validate event title
    if(empty(trim($_POST['event_title']))) {

        $errors['event_title'] = "Event title is required.";

    } else {

        $event_title = trim($_POST['event_title']);

    }

    // Validate event location
    if(empty(trim($_POST['event_location']))) {

        $errors['event_location'] = "Event location is required.";

    } else {

        $event_location = trim($_POST['event_location']);

    }

    // Validate description
    if(empty(trim($_POST['description']))) {

        $errors['description'] = "Description is required.";

    } elseif(strlen(trim($_POST['description'])) > 255) {

        $errors['description'] = "Description cannot exceed 255 characters.";

    } else {

        $description = trim($_POST['description']);

    }

    // Validate event date
    if(empty(trim($_POST['event_date']))) {
        $errors['event_date'] = "Event date is required.";
    } else {
        $event_date = trim($_POST['event_date']);
    }

    // Validate price per person
    if(empty(trim($_POST['price_per_person']))) {

        $errors['price_per_person'] = "Price per person is required.";

    } elseif(!is_numeric($_POST['price_per_person'])) {

        $errors['price_per_person'] = "Price per person must be a number.";

    } else {

        $price_per_person = trim($_POST['price_per_person']);
    }

    // Validate event image path
    if(empty(trim($_POST['event_imagepath']))) {

        $errors['event_imagepath'] = "Event image path is required.";

    } elseif(!preg_match('/\.(jpg|png)$/', $_POST['event_imagepath'])) {

        $errors['event_imagepath'] = "Only jpg or png images are allowed.";

    } else {

        $event_imagepath = trim($_POST['event_imagepath']);

    }

    // Validate promotion
    $promotion = isset($_POST['promotion']) && $_POST['promotion'] == '1' ? 1 : 0;
    // Validate top pick
    $top_pick = isset($_POST['top_pick']) && $_POST['top_pick'] == '1' ? 1 : 0;

    if (empty($errors)){
        // Prepare an SQL statement to insert new event
        $sqlQuery = "INSERT INTO events (event_title, event_location, description, event_date, price_per_person, event_imagepath, promotion, top_pick) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($conn, $sqlQuery)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssdsii", $event_title, $event_location, $description, $event_date, $price_per_person, $event_imagepath, $promotion, $top_pick);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {

                $eventMsg = "<h3><span class='success'>New event added successfully.</span></h3>";

            } else {

                $eventMsg = "Something went wrong. Please try again later.";

            }

            // Close statement
            mysqli_stmt_close($stmt);
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

                <h1><?php echo $pageTitle ?></h1>

                <p>What would you like to do?</p>
                <!-- Create the dashboard menu from template -->
                <?php echo makeUserDashButtons(); ?>

                <br>

                <?php // Only show the message for form once submitted
                    if (isset($eventMsg)) {
                        echo $eventMsg;
                    }
                ?>
                <!-- Form to add new event -->
                <h3>Add an Event (Complete all fields)</h3>
                        <form class="input-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" onsubmit="return confirmAction('add this event');">
                            <label for="event_title">Event Title:</label>
                            <input type="text" id="event_title" name="event_title" >
                            <span class="error"><?php echo isset($errors['event_title']) ? '<br/>'.$errors['event_title'].'<br/>' : ''; ?></span>

                            <label for="event_location">Location:</label>
                            <input type="text" id="event_location" name="event_location" >
                            <span class="error"><?php echo isset($errors['event_location']) ? '<br/>'.$errors['event_location'].'<br/>' : ''; ?></span>

                            <label for="description">Description:</label>
                            <textarea id="description" name="description" rows="4" maxlength="255" ></textarea>
                            <span class="error"><?php echo isset($errors['description']) ? '<br/>'.$errors['description'].'<br/>' : ''; ?></span>

                            <label for="event_date">Event Date:</label>
                            <input type="date" id="event_date" name="event_date" >
                            <span class="error"><?php echo isset($errors['event_date']) ? '<br/>'.$errors['event_date'].'<br/>' : ''; ?></span>

                            <label for="price_per_person">Price Per Person:</label>
                            <input type="text" id="price_per_person" name="price_per_person" min="0" step="0.01" >
                            <span class="error"><?php echo isset($errors['price_per_person']) ? '<br/>'.$errors['price_per_person'].'<br/>' : ''; ?></span>

                            <label for="event_imagepath">Image Name:</label>
                            <input type="text" id="event_imagepath" name="event_imagepath" >
                            <span class="error"><?php echo isset($errors['event_imagepath']) ? '<br/>'.$errors['event_imagepath'].'<br/>' : ''; ?></span>

                            <label for="promotion">Promotion:</label>
                            <select id="promotion" name="promotion">
                                <option value="0" selected>No</option>
                                <option value="1">Yes</option>
                            </select>

                            <label for="top_pick">Top Pick:</label>
                            <select id="top_pick" name="top_pick">
                                <option value="0" selected>No</option>
                                <option value="1">Yes</option>
                            </select>

                            <input class="primary-button" type="submit" value="Add Event">
                        </form>
            </div>
        </div>
    </main>

<?php
echo makeFooter();