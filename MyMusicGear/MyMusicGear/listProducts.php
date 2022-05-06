<?php
include('functions.php');

//class instances
$validate = new Validate();
$list = new ViewingProducts();
$error = new Errors();

//validate the user is logged in
if (!$validate->isLoggedIn()) {
    echo "<div style=' position: relative; display: inline-block; top: 50px; left: 550px'>
            <img src=images/test>
            <div style=' position: absolute; z-index: 999;margin: 0 auto;left: 0;right: 0;top: 45%; /* Adjust this value to move the positioned div up and down */text-align: center;width: 60%;;'>
                <h3>You must log in to use<br> this feature</h3>
                <a href='MyMusicGear.php?logout=1' style='color: red; vertical-align:middle; margin: 170px'>Login</a>
            </div> 
          </div>";
}
?>
<!--page behaviour if the account is an admin-->
<?php if ($validate->isAdmin()) : ?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Home</title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <style>
            .header {
                background: #003366;
            }
        </style>
    </head>

    <body>
        <div class="header">
            <h2>View Products</h2>
            <!--openLogin script at bottom of section-->
            <button class="tablinksAdmin" onclick="openLogin('All','AllButton')" id="AllButton">All</button>
            <button class="tablinksAdmin" onclick="openLogin('Available','AvaButton')" id="AvaButton">Available</button>
            <button class="tablinksAdmin" onclick="openLogin('Unavailable','UnavaButton')" id="UnavaButton">Unavailable</button>
            <button class="tablinksAdmin" onclick="openLogin('Overdue','ODButton')" id="ODButton">Overdue</button>
            <form method="post" action="MyMusicGear.php">
                <button type="submit" name="function" value="search" class="tablinksAdmin">Search</button>
            </form>
        </div>

        <div class="content">

            <!-- logged in user information -->
            <div class="profile_info" style="float: left">
                <img src="images/Instruments.png" style="width:100px;height:100px;">
                <small style="display: block">
                    <a href="MyMusicGear.php?home=1" style="color: red;">Home</a>
                    <a href="MyMusicGear.php?logout='1'" style="color: red;">Logout</a>
                </small>
            </div>

            <!--various sections displayed by the OpenLogin function-->
            <div class="innercontent">
                <?php echo $error->display_error(); ?>
                <div id="All" class="tabcontent">
                    <?php
                    $list->showAllProducts();
                    ?>
                </div>

                <div id="Available" class="tabcontent">
                    <?php
                    $list->showAvailableProducts();
                    ?>
                </div>

                <div id="Unavailable" class="tabcontent">
                    <?php
                    $list->showUnavailableProducts();
                    ?>
                </div>


                <div id="Overdue" class="tabcontent">
                    <?php
                    $list->showOverdueProducts()
                    ?>
                </div>
            </div>

        </div>

        <script>
            function openLogin(loginType, buttonName) {
                var i, tabcontent;

                // Get all elements with class="tabcontent" and hide them
                tabcontent = document.getElementsByClassName("tabcontent");
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }

                // Get all elements with class="tablinksAdmin" and remove the class "clicked"
                tablinks = document.getElementsByClassName("tablinksAdmin");
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].classList.remove("clicked");
                }

                // Show the current tab
                document.getElementById(loginType).style.display = "block";
                document.getElementById(buttonName).classList.add("clicked");

            }

            <?php if (!empty($_POST['function'])) : ?>
                // Get the element with clicked id and click on it
                document.getElementById("AllButton").click();
                document.getElementById("AllButton").classList.add("clicked");
            <?php endif; ?>
            <?php if (isset($GET['setAvailable']) && $_GET['setAvailable'] == 1) : ?>
                // Get the element with clicked id and click on it
                document.getElementById("UnavaButton").click();
                document.getElementById("UnavaButton").classList.add("clicked");
            <?php endif; ?>
            <?php if (isset($GET['setAvailable']) && $_GET['setAvailable'] == 0) : ?>
                // Get the element with clicked id and click on it
                document.getElementById("AvaButton").click();
                document.getElementById("AvaButton").classList.add("clicked");
            <?php endif; ?>
        </script>
    </body>

    </html>
<?php endif; ?>
<!-- page behaviour if the account is a client-->
<?php if (!$validate->isAdmin()) : $_SESSION['listDisp'] = $_POST["function"]; ?>
    <!--behaviour if user is viewing avaiable products-->
    <?php if ($_SESSION['listDisp'] == 'view') : ?>
        <!DOCTYPE html>
        <html>

        <head>
            <title>View Products</title>
            <link rel="stylesheet" type="text/css" href="style.css">
            <style>
                .header {
                    background: #5F9EA0;
                }
            </style>
        </head>

        <body>

            <div class="header">
                <h2>Available Products</h2>
                <form method="post" action="MyMusicGear.php">
                    <button type="submit" name="function" value="search" class="tablinks">Search</button>
                    <button type="submit" name="function" value="renting" class="tablinks">My Rentals</button>
                    <button type="submit" name="function" value="hist" class="tablinks">Rental History</button>
                </form>
            </div>

            <div class="content">

                <!-- logged in user information -->
                <div class="profile_info" style="float: left">
                    <img src="images/Instruments.png" style="width:100px;height:100px;">
                    <small style="display: block">
                        <a href="MyMusicGear.php?home=0" style="color: red;">Home</a>
                        <a href="MyMusicGear.php?logout='1'" style="color: red;">Logout</a>
                    </small>
                </div>

                <div class="innercontent">

                    <div id="Available" class="tabcontent">
                        <?php
                        $list->showAvailableProducts();
                        ?>
                    </div>
                </div>

            </div>
        </body>

        </html>
    <?php endif; ?>
    <!-- behaviour if user is viewing their history-->
    <?php if ($_SESSION['listDisp'] == 'hist') : ?>
        <!DOCTYPE html>
        <html>

        <head>
            <title>Home</title>
            <link rel="stylesheet" type="text/css" href="style.css">
            <style>
                .header {
                    background: #5F9EA0;
                }
            </style>
        </head>

        <body>

            <div class="header">
                <h2>Rental History</h2>
                <form method="post" action="MyMusicGear.php">
                    <button type="submit" name="function" value="search" class="tablinks">Search</button>
                    <button type="submit" name="function" value="view" class="tablinks">Available Products</button>
                    <button type="submit" name="function" value="renting" class="tablinks">My Rentals</button>
                </form>
            </div>

            <div class="content">

                <!-- logged in user information -->
                <div class="profile_info" style="float: left">
                    <img src="images/Instruments.png" style="width:100px;height:100px;">
                    <small style="display: block">
                        <a href="MyMusicGear.php?home=0" style="color: red;">Home</a>
                        <a href="MyMusicGear.php?logout='1'" style="color: red;">Logout</a>
                    </small>
                </div>

                <div class="innercontent">

                    <div id="Rented" class="tabcontent">
                        <?php
                        $list->showRentedProducts($_SESSION['user']['ID']);
                        ?>
                    </div>
                </div>

            </div>
        </body>

        </html>
    <?php endif; ?>
    <!-- behaviour if user is viewing their current rentals-->
    <?php if ($_SESSION['listDisp'] == 'renting') : ?>
        <!DOCTYPE html>
        <html>

        <head>
            <title>Home</title>
            <link rel="stylesheet" type="text/css" href="style.css">
            <style>
                .header {
                    background: #5F9EA0;
                }
            </style>
        </head>

        <body>

            <div class="header">
                <h2>Current Rentals</h2>
                <form method="post" action="MyMusicGear.php">
                    <button type="submit" name="function" value="search" class="tablinks">Search</button>
                    <button type="submit" name="function" value="view" class="tablinks">Available Products</button>
                    <button type="submit" name="function" value="hist" class="tablinks">Rental History</button>
                </form>
            </div>

            <div class="content">

                <!-- logged in user information -->
                <div class="profile_info" style="float: left">
                    <img src="images/Instruments.png" style="width:100px;height:100px;">
                    <small style="display: block">
                        <a href="MyMusicGear.php?home=0" style="color: red;">Home</a>
                        <a href="MyMusicGear.php?logout='1'" style="color: red;">Logout</a>
                    </small>
                </div>

                <div class="innercontent">

                    <div id="Renting" class="tabcontent">
                        <?php
                        $list->showRentingProducts($_SESSION['user']['ID']);
                        ?>
                    </div>
                </div>

            </div>
        </body>

        </html>
    <?php endif; ?>
<?php endif; ?>