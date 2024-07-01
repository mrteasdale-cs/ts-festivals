<?php

//Function to auto log user out
function sessionExpire(){

    // set timeout period in seconds
    $inactive = 720; // 12 minutes

    $session_life = time() - $_SESSION['timeout'];

    if($session_life > $inactive) {

        session_destroy(); // This works
        echo "<script>alert('Your session has expired! Please log in again.');</script>";
        header('logout.php?status=inactive'); // Redirect to the login page again
    }

}
function promoDiscount($promotion, $price){

    // Change for bigger/smaller discount on select events - change value from 0 to 1 in events table
    $DISCOUNT = 0.4;
    $Pprice = 0.0;

    if($promotion){
        $Pprice = round($price - ($price * $DISCOUNT),2);
        $offerText = "40% PROMO! Was £" . $price . " now ";
    } else{
        $Pprice = round($price,2);
        $offerText = "";
    }
    return array($offerText , $Pprice);
}

function validateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

// Search functionality and event display
$searchWord = "";
function eventFilter($searchWord)
{
    // Let's check if a search term exists from the searchbar. if so, we can use this in our query instead
    if (array_key_exists('search', $_POST)) {

        // Grab the recent search term and pass into the SQL query
        $searchWord = $_POST['search'];
        //create query to retrieve all events from events table
        $selectEvents = "SELECT * FROM events 
                                WHERE event_title LIKE '%$searchWord%' OR event_location LIKE '%$searchWord%' OR description LIKE '%$searchWord%'";

    } else if (array_key_exists('cost', $_POST)) {

        $filterCost = $_POST['cost'];
        //create query to retrieve all events from events table
        $selectEvents = "SELECT * FROM events WHERE price_per_person < '$filterCost' ORDER BY price_per_person";

    } else {

        $selectEvents = "SELECT * FROM events"; //just select all records when not searching

    }

    return $selectEvents;
}

/*TESTING
function checkEmptySearch(){

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $term = htmlspecialchars($_POST['search']);
        if (!empty($term)) {
            return "events.php";
        }
    }
}*/

