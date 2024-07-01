<?php
// Registration page. Allows users to register on the site.
// import all files
global $conn;
require_once __DIR__ . '/../assets/templates/db_conn.php';
require_once __DIR__ . '/../assets/templates/template_functions.php';

echo makeHeader("T's Festivals");
echo makeNavMenu();
// Field Names for ref `customerID`, `password_hash`, `customer_forename`, `customer_surname`,`customer_email`, `date_of_birth`
// Initialise variables
$firstname = $surname = $email = $date_of_birth = $passw = $conf_pass = "";
$email_err = $pass_err = $conf_pass_err = $details_err = $fname_err = $sname_err = $dob_err = "";

// Check if the user is logged in, if not then redirect to login page
if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {
    if (isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] === 1) {
        header("location: admin.php");
    } else {
        header("location: dashboard.php");
        exit;
    }
}

// Check if form submitted via php self POST
if($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate the user information
    if(empty(trim($_REQUEST["customer_forename"]))){

        $fname_err = "Firstname cannot be blank";

    } else {

        if (strlen(trim($_REQUEST["customer_forename"])) < 3 || strlen(trim($_REQUEST["customer_forename"])) > 255) {

            $fname_err = "Name must be greater than 3 and less than 255 characters";

        }else {

            $firstname = $_REQUEST["customer_forename"];

            // Use preg match to catch Chinese and Greek characters and reject them
            // it would probably be easier to encode the db differently to accept unicode but this was easier
            // to implement after I had my partner test my registration form and she used mandarin letters which broke my db
            if (preg_match('/[\p{Han}]/u', $firstname) or preg_match('/[\p{Greek}]/u', $firstname)) {

                $fname_err = "Please use English characters.";

            } else {

                $firstname = htmlspecialchars($firstname);
            }

            $firstname = htmlspecialchars($_REQUEST["customer_forename"]);
        }
    }

    if(empty(trim($_REQUEST["customer_surname"]))){

        $sname_err = "Surname cannot be blank";

    } else {

        if (strlen(trim($_REQUEST["customer_surname"])) < 3 || strlen(trim($_REQUEST["customer_surname"])) > 255) {

            $sname_err = "Name must be greater than 3 and less than 255 characters";

        } else {

            $surname = htmlspecialchars($_REQUEST["customer_surname"]);

            //Experimenting with rejecting Chinese and Greek characters after I had someone test my site and they broke it using Traditional Chinese Chars
            if (preg_match('/[\p{Han}]/u', $firstname) or preg_match('/[\p{Greek}]/u', $firstname)) {

                $sname_err = "Please use English characters.";

            } else {

                $surname = htmlspecialchars($surname);
            }

        }
    }

    // validate the email
    if(empty(trim($_REQUEST["customer_email"]))){

        $email_err = "Please enter an email";

    } else {
        // Prepare an SQL statement to get users emails
        $sqlQuery = "SELECT customerID from customers WHERE customer_email = ?";

        if ($stmt = mysqli_prepare($conn, $sqlQuery)){

            mysqli_stmt_bind_param($stmt, "s", $email);

            $email = htmlspecialchars($_REQUEST["customer_email"]);
            $email = filter_var($email, FILTER_VALIDATE_EMAIL);

            // Execute the statement
            if(mysqli_stmt_execute($stmt)){
                // Store result in variable
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){

                    // Set error message for form if email is equal to 1 meaning another exists
                    $email_err = "Email already taken. Choose another.";
                } else {

                    $email = htmlspecialchars($_POST["customer_email"]);

                }
            } else {

                echo "Whoops, something went wrong";

            }
            // Close the statement
            mysqli_stmt_close($stmt);
        }
    }

    //Validate date field
    if (empty(trim($_REQUEST["date_of_birth"]))) {

        $dob_err = "Date of birth cannot be blank";

    } else {
            // Eventually got this working with the help of
            // https://www.php.net/manual/en/datetime.diff.php & https://www.w3schools.com/PHP/phptryit.asp?filename=tryphp_func_date_diff
            // although this is procedural style
            $date_of_birth = $_REQUEST["date_of_birth"];
            $input_date = new DateTime($date_of_birth);
            $current_date = new DateTime();

            $interval = date_diff($input_date, $current_date);
            $age_days = $interval->format('%R%a days');

            if (($age_days/365) < 18) {
                $dob_err = "Must be at least 18 years old to register";
            } else {
                $date_of_birth = $_REQUEST["date_of_birth"];
            }
        }

    // Validate the password field
    if (empty(trim($_REQUEST["password"]))){ // Check if empty

        $pass_err = "Password cannot be blank";

    } elseif (strlen(trim($_REQUEST["password"])) < 8){ // Check if length is correct

        $pass_err = "Password must be minimum 8 characters";

        // Check if patten matches requirements. Some support from Stack Overflow on this one
    } elseif (preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,25}$/',$passw)) {

        $pass_err = "Password must contain at least one lowercase, uppercase, digit and !@#$%.";

    } else {

        $passw = htmlspecialchars($_REQUEST["password"]);

    }
    // Validate the confirm your password field
    if (empty(trim($_REQUEST["confirm_password"]))){

        $conf_pass_err = "Please confirm password";

    } else {

        $conf_pass = htmlspecialchars($_REQUEST["confirm_password"]);

        if (empty($pass_err) && ($passw != $conf_pass)){

            $conf_pass_err = "Passwords did not match.";

        }
    }
    // Lets begin the insert query
    if (empty($email_err) && empty($pass_err) && empty($conf_pass_err) && empty($details_err) && empty($dob_err)) {
        // Setup sql query to be used later
        $sqlQuery = "INSERT INTO customers (password_hash, customer_forename, customer_surname,customer_email, date_of_birth)
                    VALUES (?,?,?,?,?)";

        // Prepare query
        if ($stmt = mysqli_prepare($conn, $sqlQuery)) {

            $date_of_birth = htmlspecialchars($_REQUEST["date_of_birth"]);

            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $Hash_password, $firstname, $surname, $email, $date_of_birth);
            // Prepare parameters to be executed
            $Hash_password = password_hash($passw, PASSWORD_DEFAULT);

            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to login page if successful and start a new session
                session_start();
                // Store success in session data to check login page for registration
                $_SESSION["reg_success"] = true;

                header("location: login.php");

            }
            // Close query to release resources
            mysqli_stmt_close($stmt);
        }
    }
}
$pageTitle = "Registration";
?>
        <main>
            <div id="secondary-banner">
                <?php echo makeSearchBar();?>
            </div>
            <div id="content">

                <h1><?php echo $pageTitle ?></h1>

                <!-- REGISTRATION FORM -->
                <form class="input-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                    <div class="group">
                        <label for="customer_forename">Firstname: </label>
                        <input type="text" id="customer_forename" name="customer_forename" value="<?php echo $firstname; ?>" placeholder="Joe"> <!--pattern=[A-Z\sa-z]{3,20}-->
                        <span class="error"><br /><?php echo $fname_err; ?></span>
                    </div>
                    <div class="group">
                        <label for="customer_surname">Surname: </label>
                        <input type="text" id="customer_surname" name="customer_surname" value="<?php echo $surname; ?>" placeholder="Bloggs">
                        <span class="error"><br /><?php echo $sname_err; ?></span>
                    </div>
                    <div class="group">
                        <label for="customer_email">E-mail: </label>
                        <input type="email" id="customer_email" name="customer_email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>" placeholder="jBloggs@email.com">
                        <span class="error"><br /><?php echo $email_err; ?></span>
                    </div>
                    <div class="group">
                        <label for="date_of_birth">Date of Birth</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo $date_of_birth; ?>">
                        <span class="error"><br /><?php echo $dob_err; ?></span>
                    </div>
                    <div class="group">
                        <label for="password">Password: </label>
                        <input type="password" id="password" name="password" value="<?php echo $passw; ?>">
                        <span class="error"><br /><?php echo $pass_err; ?></span>
                    </div>
                    <div class="group">
                        <label for="confirm_password">Retype Password: </label>
                        <input type="password" id="password" name="confirm_password" value="<?php echo $conf_pass; ?>">
                        <span class="error"><?php echo $conf_pass_err; ?></span>
                    </div>
                    <button class="primary-button" type="submit">Register</button>
                    <button class="primary-button" onclick="window.location.href = '/index.php'">Back</button>
                </form>

                <!-- To top navigation button function -->
                <?php echo makeToTopNav(); ?>

            </div>

        </main>
<?php
echo makeFooter();
