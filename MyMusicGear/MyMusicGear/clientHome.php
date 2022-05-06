<?php
include('functions.php');
$_SESSION['entry'] = "logged";
$validate = new Validate();

if (!$validate->isLoggedIn()) {
    header('location: MyMusicGear.php');
}
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
<?php if ($validate->isLoggedIn() && !$validate->isAdmin()) : ?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Home</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>

    <body>
        <div class="header">
            <h2>Home Page</h2>
        </div>
        <div class="content">
            <!-- notification message -->
            <?php if (isset($_SESSION['success'])) : ?>
                <div class="error success">
                    <h3>
                        <?php
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                        ?>
                    </h3>
                </div>
            <?php endif ?>
            <!-- logged in user information -->
            <div class="profile_info">
                <img src="images/user_profile.png">

                <div>
                    <?php if (isset($_SESSION['user'])) : ?>
                        <strong><?php echo ucfirst($_SESSION['user']['Name']) . " " . ucfirst($_SESSION['user']['Surname']); ?></strong>

                        <small>
                            <i style="color: #888;">(<?php echo ucfirst($_SESSION['user']['Type']); ?>)</i>
                            <br>
                        </small>
                        <!--buttons for site navigation-->
                        <form class="innerform" method="post" action="MyMusicGear.php">
                            <button type="submit" class="btn" name="function" value="search">Search Products</button>
                            <button type="submit" class="btn" name="function" value="view">Available Products</button>
                            <button type="submit" class="btn" name="function" value="hist">Rental History</button>
                            <button type="submit" class="btn" name="function" value="renting">My Rentals</button>
                        </form>



                        <small>
                            <a href="MyMusicGear.php?logout='1'" style="color: red;">Logout</a>
                        </small>

                    <?php endif ?>
                </div>
            </div>
        </div>
    </body>

    </html>
<?php endif; ?>