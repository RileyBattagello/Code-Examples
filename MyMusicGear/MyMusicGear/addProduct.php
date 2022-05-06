<?php
include('functions.php');
$validate = new Validate();
$error = new Errors();

if (!$validate->isAdmin()) {
    echo "<div style=' position: relative; display: inline-block; top: 50px; left: 550px'>
            <img src=images/test>
            <div style=' position: absolute; z-index: 999;margin: 0 auto;left: 0;right: 0;top: 45%; /* Adjust this value to move the positioned div up and down */text-align: center;width: 60%;;'>
                <h3>You must log in to use<br> this feature</h3>
                <a href='MyMusicGear.php?logout=1' style='color: red; vertical-align:middle; margin: 170px'>Login</a>
            </div> 
          </div>";
}
?>
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
            <h2>Add A New Product</h2>
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

            <form class="innercontent" method="post" action="addProduct.php">
                <?php echo $error->display_error(); ?>
                <div class="input-group">
                    <label>Category</label>
                    <select name="category">
                        <option value="Guitar">Guitar</option>
                        <option value="Drums">Drums</option>
                        <option value="KeyBoard">KeyBoard</option>
                        <option value="Amplifier">Amplifier</option>
                        <option value="Accessory">Accessory</option>
                    </select>
                </div>

                <div class="input-group">
                    <label>Brand</label>
                    <select name="brand">
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
                    <label>Year of Manufacture</label>
                    <input class="box" type="text" name="year">
                </div>

                <div class="input-group">
                    <label>Characteristics</label>
                    <div style="margin-left: 15px;">
                        <small><label>Condition</label></small>
                        <select name="condition">
                            <option value="Never Used">Never Used</option>
                            <option value="Restored">Restored</option>
                            <option value="Like New">Like New</option>
                            <option value="Slightly Used">Slightly Used</option>
                            <option value="Used">Used</option>
                            <option value="Damaged">Damaged</option>
                        </select>
                        <small><label>Colour</label></small>
                        <select name="colour">
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
                    <label>Cost per day</label>
                    <input class="box" style="padding: 5px 4px;" type="number" min="1" step="0.1" name="costPD">
                </div>

                <div class="input-group">
                    <label>Cost per day overdue</label>
                    <input class="box" style="padding: 5px 4px;" type="number" min="1" step="0.1" name="costOD">
                </div>

                <div class="input-group">
                    <label>Availability Status</label>
                    <select name="status">
                        <option value="1">Available</option>
                        <option value="0">Unavailable</option>
                    </select>
                </div>
                <br>

                <div class="input-group">
                    <button type="submit" class="btn" name="insert" value="insert">Add Product
                    </button>
                </div>


            </form>
        </div>
        </div>
    </body>

    </html>
<?php endif; ?>