<?php
include('functions.php');
$_SESSION['entry'] = "logged";

//declaring instance of validate
$validate = new Validate();
//checking user is an admin account
//not admin
if (!$validate->isAdmin()) {
    echo "<div style=' position: relative; display: inline-block; top: 50px; left: 550px'>
            <img src=images/test>
            <div style=' position: absolute; z-index: 999;margin: 0 auto;left: 0;right: 0;top: 45%; /* Adjust this value to move the positioned div up and down */text-align: center;width: 60%;;'>
                <h3>You must log in to use<br> this feature</h3>
                <a href='clientHome.php?logout=1' style='color: red; vertical-align:middle; margin: 170px'>Login</a>
            </div> 
          </div>";
}
?>
<!--is admin-->
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

            button[name=register_btn] {
                background: #003366;
            }
        </style>
    </head>

    <body>
        <div class="header">
            <h2>Admin - Home Page</h2>
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
                <img src="images/admin_profile.png">

                <div>
                    <?php if ($validate->isLoggedIn()) : ?>
                        <strong><?php echo ucfirst($_SESSION['user']['Name']) . " " . ucfirst($_SESSION['user']['Surname']); ?></strong>

                        <small>
                            <i style="color: #888;">(<?php echo ucfirst($_SESSION['user']['Type']); ?>)</i>
                            <br>
                        </small>

                        <form class="innerform" method="post" action="MyMusicGear.php">
                            <button type="submit" class="btn" name="function" value="search">Search Products</button>
                            <button type="submit" class="btn" name="function" value="list">View Products</button>
                            <button type="submit" class="btn" name="function" value="Add">Add a Product</button>
                        </form>


                        <small>
                            <br><a href="MyMusicGear.php?logout='1'" style="color: red;">Logout</a>
                        </small>

                    <?php endif ?>
                </div>
            </div>
        </div>
    </body>

    </html>
<?php endif; ?>