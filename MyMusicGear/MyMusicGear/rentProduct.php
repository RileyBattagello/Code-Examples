<?php
include('functions.php');
//classinstances
$validate = new Validate();
$error = new Errors();
$calc = new Calculations();

//behaviour if user found page without being logged in
if (!$validate->isLoggedIn()) {
    echo "<div style=' position: relative; display: inline-block; top: 50px; left: 550px'>
            <img src=images/test>
            <div style=' position: absolute; z-index: 999;margin: 0 auto;left: 0;right: 0;top: 45%; /* Adjust this value to move the positioned div up and down */text-align: center;width: 60%;;'>
                <h3>You must log in to use<br> this feature</h3>
                <a href='MyMusicGear.php?logout=1' style='color: red; vertical-align:middle; margin: 170px'>Login</a>
            </div> 
          </div>";
}
//behaviour if admin account is on the page
if ($validate->isAdmin()) {
    echo "<div style=' position: relative; display: inline-block; top: 50px; left: 550px'>
            <img src=images/test>
            <div style=' position: absolute; z-index: 999;margin: 0 auto;left: 0;right: 0;top: 45%; text-align: center;width: 60%;;'>
                <h3>You must use a Client account <br> to access this feature</h3>
                <a href='MyMusicGear.php?logout=1' style='color: red; vertical-align:middle; margin: 170px'>Login</a>
            </div> 
          </div>";
}
?>
<!--user is logged into a client account-->
<?php if ($validate->isLoggedIn() && !$validate->isAdmin()) : ?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Home</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>

    <body>
        <div class="header">
            <h1>Rent Product</h1>
            <form method="post" action="MyMusicGear.php">
                <button type="submit" name="function" value="view" class="tablinks">Back</button>
            </form>
        </div>

        <div class="content">

            <!-- logged in user information -->
            <div class="profile_info" style="float: left">
                <img src="images/Instruments.png" style="width:100px;height:100px;">
                <small style="display: block">
                    <a href="clientHome.php" style="color: red;">Home</a>
                    <a href="MyMusicGear.php?logout='1'" style="color: red;">Logout</a>
                </small>
            </div>

            <div class="innercontent">
                <?php echo $error->display_error(); ?>
                <?php
                echo "<h4 style='font-size: 120%'>RENTAL REQUEST<br></h4>";
                //get the information about the selected product
                if (isset($_GET['UID'], $_GET['Cat'], $_GET['Bran'], $_GET['Year'], $_GET['Char'], $_GET['CPD'], $_GET['COD'])) {
                    $_SESSION['UID'] = $_GET['UID'];
                    $_SESSION['Cat'] = $_GET['Cat'];
                    $_SESSION['Bran'] = $_GET['Bran'];
                    $_SESSION['Year'] = $_GET['Year'];
                    $_SESSION['Char'] = $_GET['Char'];
                    $_SESSION['CPD'] = $_GET['CPD'];
                    $_SESSION['COD'] = $_GET['COD'];
                }

                //display info about selected product
                echo "<br>" . $_SESSION['Cat'] . "<br>" . $_SESSION['Bran'] . "<br>" . $_SESSION['Year'] . "<br>" . $_SESSION['Char'];
                echo "<br><br>Price per Day: $" . $_SESSION['CPD'] . "<br>Price per Day Overdue: $" . $_SESSION['COD'];
                ?>
                <!--form for selecting rental length, claculating and showing prices-->
                <form method="post" action="rentProduct.php">
                    <p><br>Length of Rental:</p>
                    <input type="radio" id="rent1" name="rentalLength" value="7" onclick="toggleSelect('7')">
                    <label for="rent1">1 Week</label><br>
                    <input type="radio" id="rent2" name="rentalLength" value="14" onclick="toggleSelect('14')">
                    <label for="rent2">2 Weeks</label><br>
                    <input type="radio" id="rent3" name="rentalLength" value="21" onclick="toggleSelect('21')">
                    <label for="rent3">3 Weeks</label><br>
                    <input type="radio" id="rent4" name="rentalLength" value="28" onclick="toggleSelect('28')">
                    <label for="rent4">4 weeks</label><br><br>

                    <!--sections for each rental length, price calculated-->
                    <div id="7" class="tabcontent" style="display: none">
                        <?php
                        $calc->calculateRental(7);
                        ?>
                        <br><br>
                    </div>

                    <div id="14" class="tabcontent" style="display: none">
                        <?php
                        $calc->calculateRental(14);
                        ?>
                        <br><br>
                    </div>

                    <div id="21" class="tabcontent" style="display: none">
                        <?php
                        $calc->calculateRental(21);
                        ?>
                        <br><br>
                    </div>

                    <div id="28" class="tabcontent" style="display: none">
                        <?php
                        $calc->calculateRental(28);
                        ?>
                        <br><br>
                    </div>

                    <button type="submit" class="btn" name="rent" value="rent">Rent Product
                    </button>
                </form>
            </div>

        </div>
        <script>
            //shows the selected lengths price
            function toggleSelect(rentalTime) {
                var i, tabcontent;

                // Get all elements with class="tabcontent" and hide them
                tabcontent = document.getElementsByClassName("tabcontent");
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }

                // Show the current tab
                document.getElementById(rentalTime).style.display = "block";
            }
        </script>
    </body>

    </html>
<?php endif; ?>