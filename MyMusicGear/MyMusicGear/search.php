<?php
include('functions.php');
//class instances
$validate = new Validate();
$error = new Errors();

//behaviour if user found page without being logged in
if (!$validate->isLoggedIn()) {
    echo "<div style=' position: relative; display: inline-block; top: 50px; left: 550px'>
            <img src=images/test>
            <div style=' position: absolute; z-index: 999;margin: 0 auto;left: 0;right: 0;top: 45%; /* Adjust this value to move the positioned div up and down */text-align: center;width: 60%;;'>
                <h3>You must log in to use<br> this feature</h3>
                <a href='clientHome.php?logout=1' style='color: red; vertical-align:middle; margin: 170px'>Login</a>
            </div> 
          </div>";
}

?>
<!-- user is logged in-->
<?php if ($validate->isLoggedIn()) : ?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Home</title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <style>
            <?php if ($validate->isAdmin()) : ?>.header {
                background: #003366;
            }

            <?php elseif (!$validate->isAdmin()) : ?>.header {
                background: #5F9EA0;
            }

            <?php endif; ?>
        </style>
    </head>

    <body>
        <div class="header">
            <h2>Search Products</h2>
            <!--navigation options change depending on if user is admin or client-->
            <?php if (!$validate->isAdmin()) : ?>
                <form method="post" action="MyMusicGear.php">
                    <button type="submit" name="function" value="view" class="tablinks">Available Products</button>
                    <button type="submit" name="function" value="renting" class="tablinks">My Rentals</button>
                    <button type="submit" name="function" value="hist" class="tablinks">Rental History</button>
                </form>
            <?php endif; ?>
            <?php if ($validate->isAdmin()) : ?>
                <form method="post" action="MyMusicGear.php">
                    <button type="submit" name="function" value="view" class="tablinksAdmin">View Products</button>
                </form>
            <?php endif; ?>
        </div>
        <div class="content">

            <!-- logged in user information -->
            <div class="profile_info" style="float: left">
                <img src="images/search.png" style="width:90px;height:70px;">
                <small style="display: block">
                    <?php if ($validate->isAdmin()) : ?>
                        <a href="MyMusicGear.php?home=1" style="color: red;">Home</a>
                        <a href="MyMusicGear.php?logout='1'" style="color: red;">Logout</a>
                    <?php elseif (!$validate->isAdmin()) : ?>
                        <a href="MyMusicGear.php?home=0" style="color: red;">Home</a>
                        <a href="MyMusicGear.php?logout='1'" style="color: red;">Logout</a>
                    <?php endif; ?>

                </small>
            </div>

            <!-- show results if a search has be requested-->
            <?php if (isset($_SESSION['SearchRes'])) : ?>
                <div class="innercontent">
                    <?php
                    echo "<h4 style='font-size: 120%'>Search Result: <br/></h4>";
                    foreach ($_SESSION['SearchRes'] as $result) {
                        echo $result . '<br>';
                    }
                    ?>
                </div>
            <?php endif ?>
            <!--search options-->
            <?php if (!isset($_SESSION['SearchRes'])) : ?>
                <form class="innercontent" method="post" action="search.php">
                    <?php echo $error->display_error(); ?>


                    <div class="input-group">

                        <label><input type="checkbox" name="catSel" id="catSel" onclick="toggleSelect('catSel','category')" />&nbsp;&nbsp;Category</label>

                        <select name="category" id="category" disabled>
                            <option value="Guitar">Guitar</option>
                            <option value="Drums">Drums</option>
                            <option value="KeyBoard">KeyBoard</option>
                            <option value="Amplifier">Amplifier</option>
                            <option value="Accessory">Accessory</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label><input type="checkbox" name="branSel" id="branSel" onclick="toggleSelect('branSel','brand')" />&nbsp;&nbsp;Brand</label>
                        <select name="brand" id="brand" disabled>
                            <option value="yamaha">Yamaha</option>
                            <option value="fender">Fender</option>
                            <option value="tama">Tama</option>
                            <option value="blackstar">Blackstar</option>
                            <option value="roland">Roland</option>
                            <option value="takamine">Takamine</option>
                            <option value="casio">Casio</option>
                            <option value="gretsch">Gretsch</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label>Characteristics</label>
                        <div style="margin-left: 10px;">
                            <small><label><input type="checkbox" name="conSel" id="conSel" onclick="toggleSelect('conSel','condition')" />&nbsp;&nbsp;Condition</label></small>
                            <select name="condition" id="condition" disabled>
                                <option value="Never Used">Never Used</option>
                                <option value="Restored">Restored</option>
                                <option value="Like New">Like New</option>
                                <option value="Slightly Used">Slightly Used</option>
                                <option value="Used">Used</option>
                                <option value="Damaged">Damaged</option>
                            </select>
                            <small><label><input type="checkbox" name="colSel" id="colSel" onclick="toggleSelect('colSel','colour')" />&nbsp;&nbsp;Colour</label></small>
                            <select name="colour" id="colour" disabled>
                                <option value="Green">Green</option>
                                <option value="Blue">Blue</option>
                                <option value="Red">Red</option>
                                <option value="Yellow">Yellow</option>
                                <option value="Orange">Orange</option>
                                <option value="Brown">Brown</option>
                                <option value="Black">Black</option>
                                <option value="White">White</option>
                            </select>
                        </div>
                    </div>
                    <div class="input-group">
                        <label><input type="checkbox" name="statSel" id="statSel" onclick="toggleSelect('statSel','status')" />&nbsp;&nbsp;Availability
                            Status</label>
                        <select name="status" id="status" disabled>
                            <option value="1">Available</option>
                            <option value="0">Unavailable</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <button type="submit" class="btn" name="search" value="search">Search Products</button>
                    </div>


                </form>
        </div>
        </div>
        <script>
            function toggleSelect(check, select) {
                var isChecked = document.getElementById(check).checked;
                document.getElementById(select).disabled = !isChecked;
            }
        </script>
    </body>

    </html>
<?php endif; ?>
<?php unset($_SESSION['SearchRes']); ?>
<?php endif; ?>