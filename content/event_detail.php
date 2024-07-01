<?php
// Event detail and booking page - used to book an event
// import all files
global $conn;
require_once __DIR__ . '/../assets/templates/db_conn.php';
require_once __DIR__ . '/../assets/templates/template_functions.php';

echo makeHeader("T's Festivals");
echo makeNavMenu();
// Initialise a session
//session_start();

// Initialise variables
$booking_notes = "";
$pageTitle = "Event Booking";

?>
    <main>
        <div id="secondary-banner">
            <?php echo makeSearchBar();?>
        </div>
        <div id="content">

            <h1><?php echo $pageTitle ?></h1>
            <section class="flex-parent-section">
                <?php

                    //this is the php script to select all events from the database
                    $eventID = $_GET['eventID'];
                    $selectAllEvents = "SELECT * FROM events WHERE eventID='$eventID'";   //create query to retrieve all events from events table
                    $getEvents = mysqli_query($conn, $selectAllEvents);

                    if (mysqli_num_rows($getEvents) > 0) {

                        while ($row = mysqli_fetch_assoc($getEvents)) { //a while loop, this will display each of the variables inside the brackets for how many records of each they are

                            //declare vars and assign values from columns in db query
                            $eventTitle = $row['event_title'];
                            $eventLocation = $row['event_location'];
                            $description = $row['description'];
                            $date = $row['event_date'];
                            $price = $row['price_per_person'];
                            $imagepath = $row['event_imagepath'];
                            $promotion = $row['promotion'];

                            //prettify the date using the substr function
                            $dateRemoveTime = substr($date, 0, 10);
                            $createDate = date_create($dateRemoveTime);
                            $formatDate = date_format($createDate,"d F Y");

                            // Call the promotion function from within functions.php to apply dynamic discount via DISCOUNT constant
                            $promoPrice = promoDiscount($promotion, $price);
                            $offerText = $promoPrice[0];
                            $promoPrice = $promoPrice[1];

                            //format the html
                            echo "
                                     <section class=\"flex-child\">
                                            <h2>$eventTitle</h2>
                                                
                                                <h3>Where: $eventLocation </h3>
                                                <h3>When: $formatDate </h3>
                                               
                                                    <p>
                                                       $description
                                                    </p>
                                                         
                                                    <h3>Cost: $offerText £$promoPrice pp.</h3>
                                  ";

                            // Check if the user is logged in, if not then redirect to login page
                            if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true){

                                echo "      <!-- BOOKING FORM -->
                                            <form class=\"input-form\" action=\"booking_confirmation.php?eventID=$eventID&eventTitle=$eventTitle\" method=\"post\">
                                                <div class=\"group\">
                                                    <label for=\"customer_email\">People: </label>
                                                    <select name=\"num_people\" id=\"num_people\" onchange='changePeople()'>
                                                        <option value=\"1\" selected='selected'>1</option>
                                                        <option value=\"2\">2</option>
                                                        <option value=\"3\">3</option>
                                                        <option value=\"4\">4</option>
                                                        <option value=\"5\">5</option>
                                                    </select>
                                                     <script>          
                                                        function changePeople(){
                                                            var people = document.getElementById('num_people');        
                                                            var selectedValue = people.value;
                                                            var total_cost = $price * selectedValue;
                                                            document.getElementById('total_cost').innerHTML = '£' + total_cost;
                                                        }
                                                    </script>
                                                </div>
                                                <div class='group'>
                                                    <label for='customer_surname'>Booking Notes: </label>
                                                    <textarea id=\"booking_notes\" name=\"booking_notes\" rows=\"3\" required>$booking_notes</textarea>
                                                </div>
                                                <div class='group'>
                                                    <label for='customer_surname'>Total Cost: </label>
                                                    <input id=\"price\" type=\"hidden\" name=\"price\" value=\"$promoPrice\">
                                                    <div id='total_cost'>
                                                            £$promoPrice
                                                    </div>
                                                                                                       
                                                </div>
                                                
                                                <div id=\"paypal-button-container\"></div>                                       
                                                
                                                <button class=\"primary-button\" type=\"submit\">Book it!</button>
                                                <button class=\"primary-button\" type=\"button\" onclick=\"window.location.href = '../events.php'\">Back to events</button>
                                            </form>
                                            

                                            
                                ";
                            }else{
                                echo "<span class=\"important\">Must be logged in to book events.</span>
                                        <br /><br />
                                        <button class=\"primary-button\" type=\"submit\" onclick=\"window.location.href = '../login.php'\">Log in</button>
                                        <button class=\"primary-button\" type=\"submit\" onclick=\"window.location.href = '../events.php'\">Back to events</button>
                                       ";
                            }

                            echo "
                               </section>
                               <section class=\"flex-child\">
                                                        
                                    <img class='big-image' src=\"../assets/images/$imagepath\" alt=\"image of $eventTitle\"/>
                                    <br/><span class=\"credits\">Credit: cntraveller.com, Sarah Koury, Getty Images, Tim Buiting, & Jack Kimber Photography </span>

                                </section>
                             ";
                        }
                    }//clean up
                mysqli_free_result($getEvents);
                mysqli_close($conn);
                ?>
            </section>
        </div>
    </main>
<?php
echo makeFooter();