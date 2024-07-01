<?php
// import all files
global $conn;
require_once __DIR__ . '/../assets/templates/db_conn.php';
require_once __DIR__ . '/../assets/templates/template_functions.php';

echo makeHeader("T's Festivals");
echo makeNavMenu();

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true){
    header("location: login.php");
    exit;
}

$pageTitle = "Success";
?>

        <main>
            <div id="banner">
                <?php echo makeSearchBar();?>
            </div>
            <div id="content">

                <h1><?php echo $pageTitle ?></h1>

                <section class="flex-parent-section">

                <?php

                    // Script to update the customers information via the form on the dashboard
                    if($_SERVER["REQUEST_METHOD"] == "POST") {

                        $customerID = htmlspecialchars($_SESSION['customer_Id']);
                        $customer_email = htmlspecialchars($_SESSION['customer_email']);

                        // Draft SQL statement
                        $sqlQuery = "UPDATE customers SET customer_forename=?, customer_surname= ?, customer_email= ?, date_of_birth= ?
                        WHERE customerID = '$customerID'";

                        // Prepare SQL statement
                        if($stmt = mysqli_prepare($conn, $sqlQuery)){
                        // Bind variables
                        $customer_email = filter_var($customer_email, FILTER_SANITIZE_EMAIL);
                        mysqli_stmt_bind_param($stmt, "ssss", $customer_forename,$customer_surname, $customer_email, $date_of_birth);

                        // Returns boolean, if true then execute query
                        if(mysqli_stmt_execute($stmt)){
                        $successString = "Record updated.";

                        echo $successString;
                        }else {
                        echo "$customerID Update failed.";
                        }
                        }else {
                        echo "Error in prepare statement.";
                        }
                        }
                    ?>

                    <h3>Operating completed successfully</h3>


                </section>

                <!-- To top navigation button function -->
                <?php echo makeToTopNav(); ?>

        </main>

<?php
echo makeFooter();
