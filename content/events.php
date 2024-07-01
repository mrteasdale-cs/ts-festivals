<?php
// Page to generate all events from db
// import all files
global $conn;
require_once __DIR__ . '/../assets/templates/db_conn.php';
require_once __DIR__ . '/../assets/templates/template_functions.php';

echo makeHeader("T's Festivals");
echo makeNavMenu();
// Inititalise variables
$pageTitle = "All Events";
?>

        <main>
            <div id="secondary-banner">
                <?php echo makeSearchBar();?>
            </div>
            <div id="content">

                <h1>
                    <?php
                        if (array_key_exists('search', $_POST)){
                                $searchWord = $_POST['search'];
                                echo "Search Results for $searchWord ";
                        }
                        else if (array_key_exists('cost', $_POST)){
                                $filterCost = $_POST['cost'];
                                echo $pageTitle . " under £" . $filterCost;
                        }
                        else{
                                echo $pageTitle;
                        }
                        ?>
                </h1>

                <div id="eventFilter">
                    <form method="POST" action="./events.php">
                        <h3>T's Festival Filter</h3>
                        <label for="cost">Choose Budget:</label>
                        <select name="cost" id="cost">
                            <option value="100">< £100</option>
                            <option value="200">< £200</option>
                            <option value="300">< £300</option>
                            <option value="600">< £600</option>
                        </select>
                        <input type="submit" value="Filter">
                    </form>
                </div>

                <section class="flex-parent-section">

                    <?php // Call the PHP function getEvents to list all events in DB - see template_functions.php
                    // Function to return events based on a search term or filter (see template_searchBar.php)
                        $offerText = $searchWord = "";
                        $sqlQuery = eventFilter($searchWord);
                        echo getEvents($conn, $sqlQuery);
                    ?>

                </section>

                <!-- To top navigation button function -->
                <?php echo makeToTopNav(); ?>

            </div>
        </main>

<?php
echo makeFooter();