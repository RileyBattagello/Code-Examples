<?php include('functions.php');
//class instace
$error = new Errors(); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>Account Registration</title>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1 style="text-align: center"><br />Welcome To My Music Gear!</h1>
    <!--client registration-->
    <div id="Client" class="tabcontent">
        <div class="header">
            <h2>New Client Registration</h2>
            <!--buttons for changing resitration type, calls openLogin function to display--->
            <button class="tablinks" onclick="openLogin('Client','clientButton2')" id="clientButton">Client</button>
            <button class="tablinks" onclick="openLogin('Administrator','adminButton2')" id="adminButton">Administrator</button>
        </div>
        <!--registration form-->
        <div class="formtest">
            <form class="mainForm" method="post" action="register.php">
                <?php echo $error->display_error(); ?>

                <div class="input-group">
                    <label>First Name</label>
                    <input class="box" type="text" name="first">
                </div>

                <div class="input-group">
                    <label>Last Name</label>
                    <input class="box" type="text" name="last">
                </div>
                <div class="input-group">
                    <label>Email</label>
                    <input class="box" type="text" name="email">
                </div>

                <div class="input-group">
                    <label>Phone</label>
                    <input class="box" type="text" name="phone">
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input class="box" type="password" name="password">
                </div>

                <div class="input-group">
                    <label>Confirm Password</label>
                    <input class="box" type="password" name="password2">
                </div>
                <p><em>(Passwords are case-sensitive and must be at least 6 characters long)</em></p>
                <input type="hidden" name="type" value='client'>
                <input type="reset" name="reset" class="btn" value="Reset Registration Form" />
                <input type="submit" name="register" class="btn" value="Register" />
                <p>Already have an account? Head to <a href="MyMusicGear.php">Login</a></p>
        </div>
        </form>
    </div>
    </div>
    <!--admin registration-->
    <div id="Administrator" class="tabcontent">
        <div class="header">
            <h2>New Administrator Registration</h2>
            <!--buttons for changing resitration type, calls openLogin function to display--->
            <button class="tablinks" onclick="openLogin('Client','clientButton')" id="clientButton2">Client</button>
            <button class="tablinks" onclick="openLogin('Administrator','adminButton')" id="adminButton2">Administrator</button>
        </div>
        <!--registration form-->
        <div class="formtest">
            <form class="mainForm" method="post" action="register.php">
                <?php echo $error->display_error(); ?>

                <div class="input-group">
                    <label>First Name</label>
                    <input class="box" type="text" name="first">
                </div>

                <div class="input-group">
                    <label>Last Name</label>
                    <input class="box" type="text" name="last">
                </div>
                <div class="input-group">
                    <label>Email</label>
                    <input class="box" type="text" name="email">
                </div>

                <div class="input-group">
                    <label>Phone</label>
                    <input class="box" type="text" name="phone">
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input class="box" type="password" name="password">
                </div>

                <div class="input-group">
                    <label>Confirm Password</label>
                    <input class="box" type="password" name="password2">
                </div>
                <p><em>(Passwords are case-sensitive and must be at least 6 characters long)</em></p>
                <input type="hidden" name="type" value='administrator'>
                <input type="reset" name="reset" class="btn" value="Reset Registration Form" />
                <input type="submit" name="register" class="btn" value="Register" />
                <p>Already have an account? Head to <a href="MyMusicGear.php">Login</a></p>

        </div>
        </form>
    </div>
    </div>

    <script>
        function openLogin(loginType, buttonName) {
            var i, tabcontent, tablinks;

            // Get all elements with class="tabcontent" and hide them
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }

            // Get all elements with class="tablinks" and remove the class "active"
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].classList.remove("clicked");
            }

            // Show the current tab
            document.getElementById(loginType).style.display = "block";
            document.getElementById(buttonName).classList.add("clicked");

        }
        // Get the element with id="defaultOpen" and click on it
        document.getElementById("clientButton").click();
        document.getElementById("clientButton").classList.add("clicked");
    </script>
</body>

</html>