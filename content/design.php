<?php
// import all files
// import all files
global $conn;
require_once __DIR__ . '/../assets/templates/db_conn.php';
require_once __DIR__ . '/../assets/templates/template_functions.php';

echo makeHeader("T's Festivals");
echo makeNavMenu();
$pageTitle = "Site Features";
?>
        <main>
            <div id="secondary-banner">
                <?php echo makeSearchBar();?>
            </div>
            <div id="content">

                <h1><?php echo $pageTitle ?></h1>

                <section class="flex-parent-section">

                    <section class=\"flex-child\">
                        <h2>Functionality</h2>
                            <p>
                                <ul>
                                    <li>
                                        Modular design. Several template files are used to create the header and footer which allows for easy customisation of things like the search bar,
                                        header navigation menu and footer style.
                                    </li>
                                    <li>
                                        Search & filter functionality. You can search for festivals based on keywords as well as filter festivals based on budget via a drop down menu.
                                    </li>
                                    <li>
                                        Secure dashboard page for bookings. Cannot access dashboard.php unless logged in. Uses PHP's SESSION function and header redirects.
                                    </li>
                                    <li>
                                        To top button for easy return to top of page.
                                    </li>
                                    <li>
                                        Secure log on using password hashing and sessions with a user dashboard to show their current bookings (pulled from DB).
                                    </li>
                                    <li>
                                        Users cannot book anything until logged in. Booking form is blocked until they are signed in.
                                    </li>
                                    <li>
                                        Promotion and top picks function. Allows admins to change a festival status to 'promotion' which applies a 40% (or whatever they choose) discount.
                                        Top picks are displayed in the home page. These are both Bool datatypes in the DB.
                                    </li>
                                    <li>
                                        Booking confirmation page that confirms user booking and provides seamless navigation back to dashboard or homepage/logout
                                    </li>
                                    <li>
                                        PayPal payments integrated. Functionality to cancel tickets
                                    </li>
                                    <li>
                                        Admin backend dashboard to allow the administration of site via special credentials. Admin can add and delete events as well as see all events/customers signed up to site.
                                        Easy to understand icons allow the user/admin to perform certain functions.
                                    </li>
                                </ul>
                            </p>
                        <h2>Design</h2>
                            <p>
                                <ul>
                                    <li>
                                        Responsive design using media queries, site converts to 'mobile' friendly when reduced to small screen with simple hamburger menu and enlarged search box/button
                                    </li>
                                    <li>
                                        Theme switcher. Dark/Night Mode
                                    </li>
                                    <li>
                                        Hover over, images grow bigger and buttons change colour etc
                                    </li>
                                    <li>
                                        Dynamic Navigation bar depedning on if user is logged in or if they are and admin / user (admin or my account shown).
                                    </li>
                                </ul>
                            </p>
                    </section>

                </section>

                <!-- To top navigation button function -->
                <?php echo makeToTopNav(); ?>

        </main>
<?php
echo makeFooter();
