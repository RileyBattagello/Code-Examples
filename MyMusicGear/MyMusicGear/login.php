<?php include('functions.php');
//class instance
$error = new Errors();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<!--login form-->
<body>
    <h1 style="text-align: center"><br />Welcome To My Music Gear!</h1>
    <div class="header">
        <h2>Login</h2>
    </div>
    <form class="mainForm" method="post" action="MyMusicGear.php">

        <?php echo $error->display_error(); ?>

        <div class="input-group">
            <label>Email</label>
            <input class="box" type="text" name="email">
        </div>
        <div class="input-group">
            <label>Password</label>
            <input class="box" type="password" name="password">
        </div>
        <div class="input-group">
            <button type="submit" class="btn" name="logIn" value="logIn">Login</button>
        </div>
        <p>
            Not yet a member? <a href="MyMusicGear.php?entry=0">Sign up</a>
        </p>
    </form>
</body>

</html>