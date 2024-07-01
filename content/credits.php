<?php
// Credits page
// import all files
require_once __DIR__ . '/../assets/templates/template_functions.php';
//create content
echo makeHeader("T's Festivals");
echo makeNavMenu();
?>

            <main>
                <div id="secondary-banner">
                    <?php echo makeSearchBar();?>
                </div>
                <div id="content">
                    <h1>Credits</h1>
                    <div class="flex-parent-section">
                        <section class="flex-child">
                            <h2>Fonts</h2>
                                <p>
                                    Font used is Roboto from Google: <a href="https://fonts.google.com/specimen/Roboto" alt="link to googles robot font" target="_blank">https://fonts.google.com/specimen/Roboto</a>
                                </p>
                        </section>
                        <section class="flex-child">
                            <h2>Other CSS Tricks</h2>
                                <p>
                                    CSS Trick for drop down menu gathered from W3Schools and CodeBoxx.com
                                </p>
                                <p>
                                    CSS transition effects learned at W3Schools (https://www.w3schools.com/css/tryit.asp?filename=trycss3_transition2)
                                </p>
                                <p>
                                    Media sizes utilised from previous experience of developing websites.
                                </p>
                        </section>
                        <section class="flex-child">
                            <h2>Festival Information</h2>
                                <p>
                                    Images and other information gathered from:
                                    <ul>
                                        <li>https://www.cntraveller.com/gallery/uk-festivals-best</li>
                                        <li>https://www.jonesaroundtheworld.com/30-music-festivals-in-europe-to-experience-before-you-die/</li>
                                    </ul>
                                </p>
                        </section>
                        <section class="flex-child">
                            <h2>All References</h2>
                            <p>
                                For full references, click <a href="../assets/docs/security-report.docx">here.</a>
                            </p>
                        </section>
                    </div>

                    <h1>Design</h1>

                        <p>Here you will find a selection of design documentation for this site.</p>

                    <h2>Wireframes</h2>

                    <div class="flex-parent-section">
                        <section class="flex-child">
                            <h3>Homepage</h3>
                                <p>
                                    <img class='big-image' src="../assets/images/wireframe1.png" alt="image of homepage wireframe"/>
                                </p>
                                <p>
                                    This is the homepage initial design.
                                </p>
                        </section>

                        <section class="flex-child">
                            <h3>Events Page</h3>
                                <p>
                                    <img class='big-image' src="../assets/images/wireframe2.png" alt="image of homepage wireframe"/>
                                </p>
                                <p>
                                    This is the events page initial design.
                                </p>
                        </section>

                        <section class="flex-child">
                            <h3>About Page</h3>
                                <p>
                                    <img class='big-image' src="../assets/images/wireframe3.png" alt="image of about page wireframe"/>
                                </p>
                                <p>
                                    This is the about page initial design.
                                </p>
                        </section>

                        <section class="flex-child">
                            <h3>Log in Page</h3>
                                <p>
                                    <img class="big-image" src="../assets/images/wireframe4.png" alt="image of log in page wireframe"/>
                                </p>
                                <p>
                                    This is the login page initial design.
                                </p>
                        </section>
                    </div>
                    <h2>Logos</h2>
                    <div class="flex-parent-section">
                        <section class="flex-child">
                            <h3>T's Festival Logo</h3>
                                <p>
                                    <img class='big-image' src="../assets/images/logo-design-ss.png" alt="logo designs"/>
                                </p>
                                <p>
                                    Two logos created on figma.com to enable responsive web design.

                                    Photo of Tent by https://www.pngarts.com/explore/107612 (2018)
                                </p>
                        </section>
                    </div>
                </div>

                <!-- To top navigation button function -->
                <?php echo makeToTopNav(); ?>

            </main>
<?php
echo makeFooter(); // create footer