<?php
// import all files
global $conn;
require_once __DIR__ . '/../assets/templates/db_conn.php';
require_once __DIR__ . '/../assets/templates/template_functions.php';

echo makeHeader("T's Festivals");
echo makeNavMenu();
$pageTitle = "T's Top Picks";
?>

        <main>
            <div id="banner">
                <?php echo makeSearchBar();?>
            </div>
            <div id="content">

                <h1><?php echo $pageTitle ?></h1>
                <section class="flex-parent-section">


                    <?php // Call the PHP function getEvents to list all events in DB - see template_functions.php
                        $sqlQuery = "SELECT * FROM events WHERE top_pick='1'";
                        echo getEvents($conn, $sqlQuery);
                    ?>


                </section>
                <br><br>
                <p>
                    <button class="primary-button" type="submit" onclick="window.location.href = 'events.php'">Checkout all events</button>
                </p>

                <!-- To top navigation button function -->
                <?php echo makeToTopNav(); ?>

            </div>
        </main>

<?php
echo makeFooter();
