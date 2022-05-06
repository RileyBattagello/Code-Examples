<?php
include('functions.php');
//class instances
$validate = new Validate();
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
            <h1>Return Product</h1>
            <form method="post" action="MyMusicGear.php">
                <button type="submit" name="function" value="renting" class="tablinks">Back</button>
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

            <!--finds and displays information about product to be returned, calculates prices-->
            <form method="post" action="returnProduct.php">
                <div class="innercontent">
                    <?php
                    echo "<h4 style='font-size: 120%'>RETURNING PRODUCT<br></h4>";
                    echo "<br>" . $_GET['Cat'] . "<br>" . $_GET['Bran'] . "<br>" . $_GET['Year'] . "<br>" . $_GET['Char'];
                    $calc->calculateReturn();
                    ?>

                    <input type="hidden" name="UID" value="<?= $_GET['UID'] ?>">
                    <br><br><button type="submit" class="btn" name="return" value="return">Proceed To Payment</button>
            </form>
        </div>

        </div>
    </body>

    </html>
<?php endif; ?>