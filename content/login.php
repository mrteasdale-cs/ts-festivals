<?php
// import all files
global $conn;
require_once __DIR__ . '/../assets/templates/db_conn.php';
require_once __DIR__ . '/../assets/templates/template_functions.php';

echo makeHeader("T's Festivals");
echo makeNavMenu();
//session_start(); // Start session for logging purposes

if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true){
    if (isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] === 1) {
        header("location: admin.php");
    }
    else{
        header("location: dashboard.php");
    }
    exit;
}
// Initialise variables
$pageTitle = "Login";
$email_err = $passw_err = $login_err = $logout ="";
$email = $passw = "";

// Check if form submitted via php self POST
if($_SERVER["REQUEST_METHOD"] == "POST") {

    // validate the email
    if (empty(trim($_REQUEST["customer_email"]))) {
        $email_err = "Please enter an email";
    } else {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    }

    // validate the password
    if (empty(trim($_REQUEST["password"]))) {
        $passw_err = "Please enter a password";
    } else {
        $passw = trim($_REQUEST["password"]);
    }

    if (empty($email_err) && empty($passw_err)) {

        // Prepare an SQL statement to get users emails
        $sqlQuery = "SELECT customerID, customer_email, password_hash, isAdmin FROM customers WHERE customer_email = ?";

        if ($stmt = mysqli_prepare($conn, $sqlQuery)) {

            mysqli_stmt_bind_param($stmt,"s", $_POST["customer_email"]);

            // Attempt to execute the statement
            if (mysqli_stmt_execute($stmt)) {

                // Store result if statement can execute
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) > 0) { // Check if rows grater than 0 so we know we have a match

                    mysqli_stmt_bind_result($stmt, $customerID, $customer_email, $password_hash, $isAdmin);

                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($passw, $password_hash)) {
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            echo "<script type='text/javascript'>alert('$isAdmin');</script>";

                            $_SESSION["logged_in"] = true;
                            $_SESSION["customer_Id"] = $customerID;
                            $_SESSION["customer_email"] = $email;
                            $_SESSION["isAdmin"] = $isAdmin;

                            // Redirect user to the appropriate page depending on user type (admin/customer)
                            if ($_SESSION["isAdmin"] == 1) {
                                header("location: admin.php");
                            } else {
                                header("location: dashboard.php");
                            }
                            exit;
                        } else {
                            // Username doesn't exist, display a generic error message
                            $login_err = "Invalid username or password.";
                        }
                    }
                } else {
                    // Username doesn't exist, display a generic error message
                    $login_err = "Invalid username or password.";
                }
            } else {
                echo "error. something went wrong.";
            }
            // Close
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

                <h1 style="text-align: center"><?php echo $pageTitle ?></h1>

                <!--(`customerID`, `password_hash`, `customer_forename`, `customer_surname`,`customer_email`, `date_of_birth`-->
                <?php
                    if (array_key_exists('reg_success', $_SESSION)) {
                        // Allows the page to display successful registration text if user has registered an account
                        $register = htmlspecialchars($_SESSION['reg_success']);
                        if ($register) {
                            echo "<h3 style=\"text-align: center\">Registration successful, you may now login!</h3>";
                        }
                    }
                ?>
                <!-- LOGIN FORM -->
                <form class="input-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                    <div class="group">
                        <label for="customer_email">E-mail: </label>
                        <input type="email" id="customer_email" name="customer_email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>" placeholder="jBloggs@email.com">
                        <span class="important"><?php echo '<br/>'.$email_err; ?></span>

                    </div>
                    <div class="group">
                        <label for="password">Password: </label>
                        <input type="password" id="password" name="password" value="<?php echo $passw; ?>"> <!--pattern=[A-Z\sa-z]{6,20}-->
                        <span class="important"><?php echo '<br/>'.$passw_err; ?></span>
                    </div>
                    <div class="group">
                        <span class="important"><?php echo $login_err; ?></span>
                    </div>
                    <div id="form-button">
                        <button class="primary-button" type="submit" style="margin-left: auto;">Login</button>
                    </div>

                    <p>Don't have an account? <a href="register.php">Register here</a>.</p>
                </form>

                <!-- LOGIN DETAILS FOR TESTING
                    ADMIN USER
                    testing@test.com
                    P@ssword1234

                    NORMAL CUSTOMER
                    myranuser
                    jbloggs@mail.com
                    testing12334

                 -->

            </div>
        </main>

<?php
echo makeFooter();