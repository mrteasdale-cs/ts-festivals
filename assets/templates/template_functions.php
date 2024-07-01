<?php
// Session start for login and session purposes
global $conn;
session_start();
// File for all template functions
require_once __DIR__ . '/functions.php';
// TEMPLATES FOR HTML STRUCTURE
// Header template
function makeHeader($title) // Function to generate header - takes title parameter
{
    $headContent = <<<HEAD
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>$title</title>
        <link id="site-style" href="/tsfestivals/assets/stylesheets/style.css" rel="stylesheet" type="text/css">
        <script src="/tsfestivals/assets/scripts/jsfunctions.js"></script>
        <!-- Fonts imported from https://fonts.google.com/specimen/Roboto -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="icon" type="image/x-icon" href="/tsfestivals/content/favicon.ico">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital@0;1&display=swap" rel="stylesheet">
    </head>
HEAD;
    $headContent .= "\n";
    return $headContent;
}

// menu template
function makeNavMenu() //CREATING FUNCTION TO GENERATE THE HEADER
{
    $navMenu = <<<NAV
    <body>
        <div id="gridcontainer">
            <header>
                <div class="siteHeader">
                    <div id="siteTitle">
                        <a href="../content/index.php"><img src="../assets/images/tlogo-white.png" alt="small tent logo"></a>
                    </div>
                    <div id="site-Title-Sm">
                        <a href="../content/index.php"><img src="../assets/images/tlogo-white-sm.png" alt="small tent logo"></a>
                    </div>
                    <nav id="header-Nav">
                        <label for="burgermenu">&#9776;</label>
                        <input type="checkbox" id="burgermenu">
                        <div id="headerItems">
                            <a href="index.php">Home</a>
                            <a href="events.php">Events</a>
                            <a href="about.php">About</a>  
                            <a href="credits.php">Credits</a>              

NAV;

    //only show specific navigation menu depending on the type of user - admin or customer
    if ((isset($_SESSION["isAdmin"]) === true && $_SESSION["isAdmin"] == 1 ) && isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {
        $navMenu .= '<a href="admin.php" class="primary-button">Admin</a>
                     <a href="logout.php" class="primary-button">Logout</a>
                      ';
    }
    elseif(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true){ //if customer
        $navMenu .= '<a href="dashboard.php" class="primary-button">My Account</a>
                     <a href="logout.php" class="primary-button">Logout</a>
                     ';
    } else {
            $navMenu .= '<a href="login.php" class="primary-button">Login</a>';
    }

    $navMenu .= <<<NAV
                    </div>
                </nav>
                <button class="theme-button" title="ThemeSwitch" onclick="switchStyle()" ></button>
            </div>
        </header>
NAV;

    $navMenu .= "\n";
    return $navMenu;
}


// search bar
function makeSearchBar() //CREATING FUNCTION TO GENERATE THE HEADER
{
    $search = <<<SEARCHBAR

        <div class="search-box"> <!-- create the search box -->
            <form method='POST' action='./events.php'>
                <input type="text" placeholder="What are you looking for?" name="search">
                <button class="primary-button" type="submit">Search!</button>
            </form>                        
        </div>
        
SEARCHBAR;
    $search .= "\n";
    return $search;
}

function makeUserDashButtons(){

    $dashButtons = <<<DASHBUTTONS

            <div id="dash-nav">

DASHBUTTONS;

    // This script will only show specific buttons for each type of user (admin or customer)
    if ((isset($_SESSION["isAdmin"]) === true && $_SESSION["isAdmin"] == 1 ) && isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {
        $dashButtons .= '
        
                    <div class="image-button">
                        <a href="admin.php"><img src="../assets/images/home.png" alt="Home"></a>
                    </div>
                    <div class="image-button">
                        <a href="admin_display_events.php"><img src="../assets/images/events.png" alt="All Events"></a>
                    </div>
                    <div class="image-button">
                        <a href="add_event.php"><img src="../assets/images/add.png" alt="Add Event"></a>
                    </div>
                    <div class="image-button">
                        <a href="all_customers.php"><img src="../assets/images/customers.png" alt="All Customers"></a>
                    </div>
                    <div class="image-button">
                        <a href="logout.php"><img src="../assets/images/log_off.png" alt="Log Off"></a>
                    </div>
        
                      ';
    }
    elseif (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true){
        $dashButtons .= '

                    <div class="image-button">
                        <a href="admin.php"><img src="../assets/images/home.png" alt="Home"></a>
                    </div>
                    <div class="image-button">
                        <a href="all_events.php"><img src="../assets/images/events.png" alt="All Events"></a>
                    </div>
                    <div class="image-button">
                        <a href="logout.php?status=logout"><img src="../assets/images/log_off.png" alt="Log Off"></a>
                    </div>

                     ';
    }

    $dashButtons .= <<<DASHBUTTONS
    
    </div>
    
DASHBUTTONS;

    $dashButtons .= "\n";
    return $dashButtons;

}

function makeToTopNav(){//function to create the to top arrow button that send user to top of page

    $toTop = <<<TOTOP
                <div id="footer-secondary">
                    <a href="#header-Nav">
                        <div id="toTop">
                            <img src="../assets/images/blue-arrow-up.png" alt="arrow up">
                        </div>
                    </a>
                </div>
TOTOP;
    $toTop .= "\n";
    return $toTop;

}

// footer template
function makeFooter() //CREATING FUNCTION TO GENERATE THE HEADER
{
    $footer = <<<FOOTER
            <footer>
                <ul id="specialUL">
                    <li><a href="events.php">Events</a></li>
                    <li><a href="credits.php">Credits</a></li>
                    <li><span id="footer-title"><a href="index.php">T'S FESTIVALS</a></span></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="logout.php?status=logout">Logout</a></li>
                </ul>
                <div id="footerDots">
                    <img src="../assets/images/footer-dots.png" alt="black dots">
                </div>
                <div id="social-media">
                    <p><strong>Connect with us:</strong></p>
                    <a href="#">
                        <img src="../assets/images/facebook-logo-icon.png" alt="fb logo">
                    </a>
                    <a href="#">
                     
                        <img src="../assets/images/whatsapp-logo_icon.png" alt="whatsapp logo">
                    </a>
                    <a href="#">
                        <img src="../assets/images/twitter-logo_icon.png" alt="twitter logo">
                    </a>
                    <a href="#">
                        <img src="../assets/images/instagram-logo_icon.png" alt="instagram logo">
                    </a>
                </div>
                <p>
                    &copy;2024 T's Festivals Ltd. design by Mr Tea. <a href="mailto:myran.teasdale@northumbria.ac.uk">email me!</a>
                </p>
            </footer>
        </div>
        <script src="../assets/scripts/jsfunctions.js"></script>
    </body>
</html>

FOOTER;
    $footer .= "\n";
    return $footer;

}

//GENERIC TEMPLATES
//Events Template
function getEvents($conn, $sqlQuery) {

    $getEvents = mysqli_query($conn, $sqlQuery);

    $rows = mysqli_num_rows($getEvents);

    if ($rows > 0) {

        while ($row = mysqli_fetch_assoc($getEvents)) //a while loop, this will display each of the variables inside the brackets for how many records of each they are
        {
            //declare vars and assign values from columns in db query
            $eventID = $row['eventID'];
            $eventTitle = $row['event_title'];
            $eventLocation = $row['event_location'];
            $description = $row['description'];
            $date = $row['event_date'];
            $price = $row['price_per_person'];
            $imagepath = $row['event_imagepath'];
            $promotion = $row['promotion'];

            //prettify the date
            $dateRemoveTime = substr($date, 0, 10);
            $createDate = date_create($dateRemoveTime);
            $formatDate = date_format($createDate,"d F Y");

            //shorten the description
            $pos = strpos($description, '.'); // Find the position of first full stop to display shorted description
            if ($pos !== false){
                $shortDesc = substr($description, 0, $pos + 1);
            } else {
                $shortDesc = $description;
            } // extract text up to full stop if containing one.

            // Call the promotion function to apply dynamic discount via DISCOUNT constant
            $Nprice = promoDiscount($promotion, $price);
            $offerText = $Nprice[0];
            $Nprice = $Nprice[1];

            //format the html
            echo "
                    <section class=\"flex-child\">
                        <h2>$eventTitle</h2>
                            <h3>Where: $eventLocation </h3>
                            <h3>When: $formatDate</h3>
                                <p>
                                    <img class='small-image' src=\"../assets/images/$imagepath\" alt=\"image of $eventTitle\">
                                    <br><span class=\"credits\">Credit: cntraveller.com, Sarah Koury, Getty Images, Tim Buiting, & Jack Kimber Photography </span>
                                </p>
                                <p>
                                    $shortDesc
                                </p>
                            <h3>Cost: $offerText £$Nprice pp.</h3>
                                <form method='POST' action='event_detail.php?eventID=$eventID'>
                                    <input type=\"hidden\" name=\"price\" value=\"$Nprice\">
                                    <button class=\"primary-button\" type=\"submit\">More info/Book</button>        
                                </form>        
                                <br>             
                    </section>
                    ";
        }
    }
    else{
        echo " <h2>No results found</h2> ";
    } //clean up
    mysqli_free_result($getEvents);
    mysqli_close($conn);

}


