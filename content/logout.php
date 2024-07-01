<?php
session_start();
session_unset(); // Unset any session variables
session_destroy(); // Stop session cookies
header('Location: login.php?logout=success'); // Redirect to the login.php page again to log the user out
exit;