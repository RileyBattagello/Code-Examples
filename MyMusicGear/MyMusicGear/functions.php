<?php
error_reporting(E_ALL);
ini_set('display_errors', FALSE);
session_start();

// connect to database
$conn = mysqli_connect('localhost', 'root', '', 'MyMusicGear');

// variable declaration
$errors = 0;
$first = "";
$last = "";
$email = "";
$phone = "";
$Body = "";
$type = "";
$errors = array();

//object declaration
$entry = new Entry();
$changeStat = new ChangeStatus();
$search = new ViewingProducts();

//if register is set call the register() function
if (isset($_POST['register'])) {
    $entry->register();
}
//if login is set call the login() function
if (isset($_POST['logIn'])) {
    $entry->login();
}
//if logout is clicked, destroy session and head to main page
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['user']);
    header("location: MyMusicGear.php");
}
//if setAvailable is set then call makeAvailable() or makeUnavailable() dependant on value
if (isset($_GET['setAvailable'])) {
    if ($_GET['setAvailable'] == 1) {
        $changeStat->makeAvailable($_GET['UID']);
    } else $changeStat->makeUnavailable($_GET['UID']);
}
//if insert is set then call insert()
if (isset($_POST['insert'])) {
    $changeStat->insert();
}
//if rent is set than call rent()
if (isset($_POST['rent'])) {
    if (isset($_POST['rentalLength'])) {
        $changeStat->rent($_SESSION['UID'], $_POST['rentalLength']);
    } else {
        array_push($errors, 'Rental Length must be selected');
    }
}
//if return is set than call makeUnavailable()
if (isset($_POST['return'])) {
    $changeStat->makeAvailable($_POST['UID']);
    $_SESSION['success'] = "Product Successfully Returned!";
    header('Location: clientHome.php');
}
//if search is set call searchProducts()
if (isset($_POST['search'])) {
    $search->searchProducts();
}

//Functions for entry into the website
class Entry
{
    //register user
    function register()
    {
        //global variable declaration
        global $conn, $errors, $email, $phone, $first, $last, $type;
        $validate = new Validate();

        if (($_POST['type'])) {
            $type = $_POST['type'];
        }

        //form validation
        if (empty($_POST['email'])) {
            array_push($errors, "Email is required");
        } else {
            $email = stripslashes($_POST['email']);
            if (preg_match("/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)*(\.[a-z]{2,3})$/i", $email) == 0) {
                array_push($errors, "You need to enter a valid e-mail address.");
                $email = "";
            }
        }

        if (empty($_POST['phone'])) {
            array_push($errors, "You need to enter a phone number.");
        } else {
            $phone = stripslashes($_POST['phone']);
            if (preg_match("/^0\\d{9}$/", $phone) == 0) {
                array_push($errors, "You need to enter a valid phone number.");
                $phone = "";
            }
        }

        if (empty($_POST['password'])) {
            array_push($errors, "You need to enter a password.");
            $password = "";
        } else
            $password = stripslashes($_POST['password']);

        if (empty($_POST['password2'])) {
            array_push($errors, "You need to enter a confirmation password.");
            $password2 = "";
        } else
            $password2 = stripslashes($_POST['password2']);

        if ((!(empty($password))) && (!(empty($password2)))) {
            if (strlen($password) < 6) {
                array_push($errors, "The password is too short.");
                $password = "";
                $password2 = "";
            }
            if ($password <> $password2) {
                array_push($errors, "The passwords do not match.");
                $password = "";
                $password2 = "";
            }
        }

        //register user if there are no errors
        if (count($errors) == 0) {
            $first = stripslashes($_POST['first']);
            $last = stripslashes($_POST['last']);
            $password = md5($password); //encrypt the password before saving in the database

            $sql = "SELECT count(*) FROM usser where Email='" . $email . "'";
            $qRes = @mysqli_query($conn, $sql);
            if ($qRes != FALSE) {
                $Row = mysqli_fetch_row($qRes);
                if ($Row[0] > 0) {
                    echo "<p>The Email entered (" . htmlentities($email) . ") is already registered.</p>\n";
                }
            } else {
                $sql = "INSERT INTO user (Name,Surname,Phone,Email,password_md5,Type) VALUES( '$first','$last','$phone','$email', '$password', '$type')";
                $qRes = @mysqli_query($conn, $sql);
                if ($qRes === FALSE) {
                    echo "<p>Unable to save your registration information. Error code " . mysqli_errno($conn) . ": " . mysqli_error($conn) . "</p>\n";
                } else {
                    $logged_in_user_id = mysqli_insert_id($conn);
                    $_SESSION['user'] = $validate->getUserById($logged_in_user_id); // put logged in user in session
                    if ($_SESSION['user']['Type'] == 'administrator') {
                        $_SESSION['success'] = "You are now logged in";
                        header('Location: home.php');
                    } else {
                        $_SESSION['success'] = "You are now logged in";
                        header('Location: clientHome.php');
                    }
                }
            }
        }
        mysqli_close($conn);
    }

    //login User
    function login()
    {
        //global variable declaration
        global $conn, $email, $errors;

        //form validation
        if (empty($_POST['email'])) {
            array_push($errors, "Username is required");
        }
        if (empty($_POST['password'])) {
            array_push($errors, "Password is required");
        }

        // attempt login if no errors
        if (count($errors) == 0) {
            $email = stripslashes($_POST['email']);
            $password = md5(stripslashes($_POST['password']));

            $query = "SELECT * FROM user WHERE Email='$email' AND password_md5='$password' LIMIT 1";
            $results = mysqli_query($conn, $query);

            if (mysqli_num_rows($results) == 1) { // user found
                // check if user is admin or user
                $logged_in_user = mysqli_fetch_assoc($results);
                if ($logged_in_user['Type'] == 'administrator') {
                    $_SESSION['user'] = $logged_in_user;
                    $_SESSION['success'] = "You are now logged in";
                    $_SESSION['Login'] = "admin";
                    $_SESSION['entry'] = "logged";
                    header('Location: MyMusicGear.php?home=1');
                } else {
                    $_SESSION['user'] = $logged_in_user;
                    $_SESSION['success'] = "You are now logged in";
                    $_SESSION['Login'] = "client";
                    $_SESSION['entry'] = "logged";
                    header('Location: MyMusicGear.php?home=0');
                }
            } else {
                array_push($errors, "Wrong username/password combination");
            }
        }
    }
}

//Functions for the validation of user information
class Validate
{
    //check if user type is admin
    function isAdmin()
    {
        if (isset($_SESSION['user']) && $_SESSION['user']['Type'] == 'administrator') {
            return true;
        } else {
            return false;
        }
    }

    //check if a user is logged in
    function isLoggedIn()
    {
        if (isset($_SESSION['user'])) {
            return true;
        } else {
            return false;
        }
    }

    // return user array from their id
    function getUserById($id)
    {
        global $conn;
        $query = "SELECT * FROM user WHERE ID=" . $id;
        $result = mysqli_query($conn, $query);
        return mysqli_fetch_assoc($result);
    }
}

//Functions for the viewing of SQL database
class ViewingProducts
{
    //Select all products in the SQL product table
    function showAllProducts()
    {
        global $conn;

        $a = "SELECT UniqueID,Category,Brand,Year,Characteristics,Status FROM product";
        if ($stmt = mysqli_prepare($conn, $a)) {
            // execute statement
            mysqli_stmt_execute($stmt);
            echo "<h4 style='font-size: 120%'>All Products: <br/><br></h4>";
            // bind result variables
            mysqli_stmt_bind_result($stmt, $s1, $s2, $s3, $s4, $s5, $s6);
            // fetch values
            while (mysqli_stmt_fetch($stmt)) {
                $charArr = preg_split("/[\n]/", "$s5");

                echo "Product ID: " . $s1 . "<br />";
                echo "Category: " . $s2 . "<br />";
                echo "Brand: " . $s3 . "<br />";
                echo "Year: " . $s4 . "<br />";
                echo "Condition: " . $charArr[0] . "<br />";
                echo "Colour: " . $charArr[1] . "<br />";
                if ($s6 == 0) {
                    echo "<a href='MyMusicGear.php?" . "setAvailable=1&" . "UID=$s1" . "'style='color: red;'>Make Available</a><br>";
                } elseif ($s6 == 1) {
                    echo "<a href='MyMusicGear.php?" . "setAvailable=0&" . "UID=$s1" . "'style='color: red;'>Make Unavailable</a><br>";
                }
                echo "<br />";
            }
            mysqli_stmt_close($stmt);
        }
    }

    //Select all unavailable products in the sql product table
    function showUnavailableProducts()
    {
        global $conn;

        $a = "SELECT UniqueID,Category,Brand,Year,Characteristics FROM product WHERE Status=0";
        if ($stmt = mysqli_prepare($conn, $a)) {
            // execute statement
            mysqli_stmt_execute($stmt);
            echo "<h4 style='font-size: 120%'> Unavailable Products: <br/><br></h4>";
            // bind result variables
            mysqli_stmt_bind_result($stmt, $s1, $s2, $s3, $s4, $s5);
            // fetch values
            while (mysqli_stmt_fetch($stmt)) {
                $charArr = preg_split("/[\n]/", "$s5");

                echo "Product ID: " . $s1 . "<br />";
                echo "Category: " . $s2 . "<br />";
                echo "Brand: " . $s3 . "<br />";
                echo "Year: " . $s4 . "<br />";
                echo "Condition: " . $charArr[0] . "<br />";
                echo "Colour: " . $charArr[1] . "<br />";
                echo "<a href='MyMusicGear.php?" . "setAvailable=1&" . "UID=$s1" . "'style='color: red;'>Make Available</a><br>";
                echo "<br />";
            }
            mysqli_stmt_close($stmt);
        }
    }

    //Select all available products in the sql product table
    function showAvailableProducts()
    {
        global $conn;
        $validate = new Validate();

        $a = "SELECT UniqueID,Category,Brand,Year,Characteristics,CostPD,CostOD FROM product WHERE Status=1";
        if ($stmt = mysqli_prepare($conn, $a)) {
            // execute statement
            mysqli_stmt_execute($stmt);
            echo "<h4 style='font-size: 120%'> Available Products: <br/><br></h4>";
            // bind result variables
            mysqli_stmt_bind_result($stmt, $s1, $s2, $s3, $s4, $s5, $s6, $s7);
            // fetch values
            while (mysqli_stmt_fetch($stmt)) {
                $charArr = preg_split("/[\n]/", "$s5");

                echo "Product ID: " . $s1 . "<br />";
                echo "Category: " . $s2 . "<br />";
                echo "Brand: " . $s3 . "<br />";
                echo "Year: " . $s4 . "<br />";
                echo "Condition: " . $charArr[0] . "<br />";
                echo "Colour: " . $charArr[1] . "<br />";
                if ($validate->isAdmin()) {
                    echo "<a href='MyMusicGear.php?" . "setAvailable=0&" . "UID=$s1" . "'style='color: red;'>Make Unavailable</a><br>";
                }
                if (!$validate->isAdmin()) {
                    $s5 = nl2br($s5);
                    echo "<a href='MyMusicGear.php?" . "setRent=0&" . "UID=$s1&" . "Cat=$s2&" . "Bran=$s3&" . "Year=$s4&" . "Char=$s5&" . "CPD=$s6&" . "COD=$s7" . " 'style='color: red;'>Rent</a><br>";
                }
                echo "<br />";
            }
            mysqli_stmt_close($stmt);
        }
    }

    //Select all overdue products in the sql product table via checking rented
    function showOverdueProducts()
    {
        global $conn;
        $today = date("Y-m-d");

        $a = "SELECT product.UniqueID,Category,Brand,Year,Characteristics FROM rented,product WHERE rented.UniqueID=product.UniqueID AND UNIX_TIMESTAMP('$today') > UNIX_TIMESTAMP(dueDate)";
        if ($stmt = mysqli_prepare($conn, $a)) {
            // execute statement
            mysqli_stmt_execute($stmt);
            echo "<h4 style='font-size: 120%'> Overdue Products: <br/><br></h4>";
            // bind result variables
            mysqli_stmt_bind_result($stmt, $s1, $s2, $s3, $s4, $s5);
            // fetch values
            while (mysqli_stmt_fetch($stmt)) {
                $charArr = preg_split("/[\n]/", "$s5");

                echo "Product ID: " . $s1 . "<br />";
                echo "Category: " . $s2 . "<br />";
                echo "Brand: " . $s3 . "<br />";
                echo "Year: " . $s4 . "<br />";
                echo "Condition: " . $charArr[0] . "<br />";
                echo "Colour: " . $charArr[1] . "<br />";
                echo "<a href='MyMusicGear.php?" . "setAvailable=1&" . "UID=$s1" . "'style='color: red;'>Make Available</a><br>";
                echo "<br />";
            }
            mysqli_stmt_close($stmt);
        }
    }

    //Select all products in the sql product table rented by the current logged in user
    function showRentingProducts($id)
    {
        global $conn;

        $a = "SELECT product.UniqueID,Category,Brand,Year,Characteristics,CostPD,CostOD FROM rented,product WHERE rented.UniqueID=product.UniqueID AND rented.renterID='$id'";
        if ($stmt = mysqli_prepare($conn, $a)) {
            // execute statement
            mysqli_stmt_execute($stmt);
            echo "<h4 style='font-size: 120%'> My Products: <br/><br></h4>";
            // bind result variables
            mysqli_stmt_bind_result($stmt, $s1, $s2, $s3, $s4, $s5, $s6, $s7);
            // fetch values
            while (mysqli_stmt_fetch($stmt)) {
                $charArr = preg_split("/[\n]/", "$s5");

                echo "Product ID: " . $s1 . "<br />";
                echo "Category: " . $s2 . "<br />";
                echo "Brand: " . $s3 . "<br />";
                echo "Year: " . $s4 . "<br />";
                echo "Condition: " . $charArr[0] . "<br />";
                echo "Colour: " . $charArr[1] . "<br />";
                echo "<a href='MyMusicGear.php?" . "setRent=1&" . "UID=$s1&" . "Cat=$s2&" . "Bran=$s3&" . "Year=$s4&" . "Char=$s5&" . "CPD=$s6&" . "COD=$s7" . " 'style='color: red;'>Return</a><br>";
                echo "<br />";
            }
            mysqli_stmt_close($stmt);
        }
    }

    //Select all distinct products in the sql rentalrecords table rented by the current logged in user
    function showRentedProducts($id)
    {
        global $conn;

        $a = "SELECT DISTINCT product.UniqueID,Category,Brand,Year,Characteristics FROM product,rentalrecords WHERE product.UniqueID=rentalrecords.UniqueID AND rentalrecords.renterID='$id'";
        if ($stmt = mysqli_prepare($conn, $a)) {
            // execute statement
            mysqli_stmt_execute($stmt);
            echo "<h4 style='font-size: 120%'> Rental History: <br/><br></h4>";
            // bind result variables
            mysqli_stmt_bind_result($stmt, $s1, $s2, $s3, $s4, $s5);
            // fetch values
            while (mysqli_stmt_fetch($stmt)) {
                $charArr = preg_split("/[\n]/", "$s5");

                echo "Product ID: " . $s1 . "<br />";
                echo "Category: " . $s2 . "<br />";
                echo "Brand: " . $s3 . "<br />";
                echo "Year: " . $s4 . "<br />";
                echo "Condition: " . $charArr[0] . "<br />";
                echo "Colour: " . $charArr[1] . "<br />";
                echo "<br />";
            }
            mysqli_stmt_close($stmt);
        }
    }

    //Return results of user's search
    function searchProducts()
    {
        //declare variables
        global $conn, $errors;
        $userID = $_SESSION['user']['ID'];
        $search = new Validate();

        //Check the parameters the user has set, if parameter is not set, set it to arbitrary value of 2 as this will never be a value that can be set from the user
        if (isset($_POST['category'])) {
            $cat = $_POST['category'];
        } else {
            $cat = 2;
        }
        if (isset($_POST['brand'])) {
            $brand = $_POST['brand'];
        } else {
            $brand = 2;
        }
        if (isset($_POST['condition'])) {
            $condition = $_POST['condition'];
        } else {
            $condition = 2;
        }
        if (isset($_POST['colour'])) {
            $colour = $_POST['colour'];
        } else {
            $colour = 2;
        }
        if (isset($_POST['status'])) {
            $status = $_POST['status'];
        } else {
            $status = 2;
        }

        //Select products that match the parameters set by the user
        $a = "SELECT product.* FROM product WHERE ('$cat' = 2 OR Category = '$cat') AND
    ('$brand' = 2 OR Brand = '$brand') AND
    ('$condition' = 2 OR Characteristics LIKE  '%$condition%') AND
    ('$colour' = 2 OR Characteristics LIKE  '%$colour%') AND
    ('$status' = 2 OR Status = '$status')";

        if (($cat == 2) && ($brand == 2) && ($condition == 2) && ($colour == 2) && ($status == 2)) {
            $a = NULL;
            array_push($errors, 'A Search term must be selected');
        }

        if ($stmt = mysqli_prepare($conn, $a)) {
            // execute statement
            mysqli_stmt_execute($stmt);
            // bind result variables
            $i = -1;
            $searchRes = array();
            mysqli_stmt_bind_result($stmt, $s1, $s2, $s3, $s4, $s5, $s6, $s7, $s8, $s9);
            mysqli_stmt_store_result($stmt);
            // fetch values
            while (mysqli_stmt_fetch($stmt)) {
                $charArr = preg_split("/[\n]/", "$s5");
                $i++;
                switch ($search->isAdmin()) {
                    case true:
                        if ($s6 == 0) {
                            $searchRes[$i] = "<br>Product ID: " . $s1 . "<br>Category: " . $s2 . "<br>Brand: " . $s3 . "<br>Year: " . $s4 . "<br>Condition: " . $charArr[0] . "<br>Colour: " . $charArr[1] . "<br><a href='MyMusicGear.php?" . "setAvailable=1&" . "UID=$s1" . "'style='color: red;'>Make Available</a><br>";
                        } elseif ($s6 == 1) {
                            $searchRes[$i] = "<br>Product ID: " . $s1 . "<br>Category: " . $s2 . "<br>Brand: " . $s3 . "<br>Year: " . $s4 . "<br>Condition: " . $charArr[0] . "<br>Colour: " . $charArr[1] . "<br><a href='MyMusicGear.php?" . "setAvailable=0&" . "UID=$s1" . "'style='color: red;'>Make Unavailable</a><br>";
                        }
                        break;
                    case false:
                        if ($s6 == 1) {
                            $searchRes[$i] = "<br>Product ID: " . $s1 . "<br>Category: " . $s2 . "<br>Brand: " . $s3 . "<br>Year: " . $s4 . "<br>Condition: " . $charArr[0] . "<br>Colour: " . $charArr[1] . "<br><a href='MyMusicGear.php?" . "setRent=0&" . "UID=$s1&" . "Cat=$s2&" . "Bran=$s3&" . "Year=$s4&" . "Char=$s5&" . "CPD=$s7&" . "COD=$s8" . " 'style='color: red;'>Rent</a><br>";
                        } elseif ($s6 == 0 && $s9 == $userID) {
                            $searchRes[$i] = "<br>Product ID: " . $s1 . "<br>Category: " . $s2 . "<br>Brand: " . $s3 . "<br>Year: " . $s4 . "<br>Condition: " . $charArr[0] . "<br>Colour: " . $charArr[1] . "<br><a href='MyMusicGear.php?" . "setRent=1&" . "UID=$s1&" . "Cat=$s2&" . "Bran=$s3&" . "Year=$s4&" . "Char=$s5&" . "CPD=$s6&" . "COD=$s7" . " 'style='color: red;'>Return</a><br>";;
                        } elseif ($s6 == 0 && $s9 <> $userID) {
                            $searchRes[$i] = "UNAVAILABLE<br>Product ID: " . $s1 . "<br>Category: " . $s2 . "<br>Brand: " . $s3 . "<br>Year: " . $s4 . "<br>Condition: " . $charArr[0] . "<br>Colour: " . $charArr[1] . "<br>UNAVAILABLE";
                        }
                        break;
                }
            }
            $_SESSION['SearchRes'] = $searchRes;
            mysqli_stmt_close($stmt);
        }
    }
}

//Functions for the altering SQL database
class ChangeStatus
{
    //Add product to the SQL table product
    function insert()
    {
        global $conn, $errors;

        if (empty($_POST['category'])) {
            array_push($errors, "Category is required");
        }
        if (empty($_POST['brand'])) {
            array_push($errors, "Brand is required");
        }
        if (empty($_POST['year'])) {
            array_push($errors, "Year is required");
        }

        if (!($_POST['year'] > 1901 && $_POST['year'] < 2155)) {
            array_push($errors, "Year must be between 1901 - 2155");
        }

        if (empty($_POST['condition'])) {
            array_push($errors, "Condition is required");
        }
        if (empty($_POST['colour'])) {
            array_push($errors, "Colour is required");
        }
        if (empty($_POST['status'])) {
            array_push($errors, "Status is required");
        }
        if (empty($_POST['costPD'])) {
            array_push($errors, "Cost Per Day is required");
        }
        if (empty($_POST['costOD'])) {
            array_push($errors, "Overdue Cost is required");
        }

        if (count($errors) == 0) {
            $category = stripslashes($_POST['category']);
            $brand = stripslashes($_POST['brand']);
            $year = stripslashes($_POST['year']);
            $condition = stripslashes($_POST['condition']);
            $colour = stripslashes($_POST['colour']);
            $status = stripslashes($_POST['status']);
            $costPD = stripslashes($_POST['costPD']);
            $costOD = stripslashes($_POST['costOD']);

            $query = "INSERT INTO product (Category,Brand,Year,Characteristics,Status,CostPD,CostOD) VALUES ('$category','$brand','$year','$condition\n$colour','$status','$costPD','$costOD')";

            $results = mysqli_query($conn, $query);

            if ($results === FALSE) {
                array_push($errors, "Unable to add the new product");
                echo "<p>Unable to select the database. " . "Error code " . mysqli_errno($conn) . ": " . mysqli_error($conn) . "</p>\n";
            } else {
                if ($status == 0) {
                    $date = date("Y/m/d");
                    $productID = mysqli_insert_id($conn);
                    $query2 = "INSERT INTO rented (UniqueID,rentedDate) VALUES ('$productID','$date')";
                    $results2 = mysqli_query($conn, $query2);
                    if ($results2 === FALSE) {
                        array_push($errors, "Unable to add the new product to rented table");
                    }
                }
                $_SESSION['success'] = "Product Added!";
                header('Location: home.php');
            }
        }
    }
    //Update products status in the SQL table product
    function makeAvailable($prod)
    {
        global $conn;

        $a = "UPDATE product SET Status = 1, renterID = 0 WHERE UniqueID='$prod'";
        $b = "DELETE FROM rented WHERE UniqueID='$prod'";

        $results = mysqli_query($conn, $a);
        if ($results === FALSE) {
            echo " < p>Unable to change product status . Error code " . mysqli_errno($conn) . ": " . mysqli_error($conn) . " </p > \n";
        } else {
            $results2 = mysqli_query($conn, $b);
            if ($results2 === FALSE) {
                echo " < p>Unable to delete product from rented table . Error code " . mysqli_errno($conn) . ": " . mysqli_error($conn) . " </p > \n";
            }
        }
    }
    //Update products status SQL table product and add to rented table
    function makeUnavailable($prod)
    {
        global $conn;
        $date = date("Y/m/d");

        $a = "UPDATE product SET Status = 0 WHERE UniqueID='$prod'";
        $b = "INSERT INTO rented (UniqueID,rentedDate) VALUES ('$prod','$date')";

        $results = mysqli_query($conn, $a);
        if ($results === FALSE) {
            echo " < p>Unable to change product status . Error code " . mysqli_errno($conn) . ": " . mysqli_error($conn) . " </p > \n";
        } else {
            $results2 = mysqli_query($conn, $b);
            if ($results2 === FALSE) {
                echo " < p>Unable to add product to rented table . Error code " . mysqli_errno($conn) . ": " . mysqli_error($conn) . " </p > \n";
            }
        }
    }
    //Update products status SQL table product and create in rented table and create in rental record
    function rent($test, $days)
    {
        global $conn;

        $date = date("Y/m/d");
        $datestr = strtotime($date);
        $date2 = strtotime("+$days day", $datestr);
        $dueDate = date('Y-m-d', $date2);
        $ID = $_SESSION['user']['ID'];

        $a = "UPDATE product SET Status = 0, renterID='$ID' WHERE UniqueID='$test'";
        $b = "INSERT INTO rented (UniqueID,renterID,rentedDate,dueDate) VALUES ('$test','$ID','$date','$dueDate')";

        $results = mysqli_query($conn, $a);
        if ($results === FALSE) {
            echo " < p>Unable to change product status . Error code " . mysqli_errno($conn) . ": " . mysqli_error($conn) . " </p > \n";
        } else {
            $results = mysqli_query($conn, $b);
            if ($results === FALSE) {
                echo " < p>Unable to add product to rented table . Error code " . mysqli_errno($conn) . ": " . mysqli_error($conn) . " </p > \n";
            } else {

                $rentalID = mysqli_insert_id($conn);
                $c = "INSERT INTO rentalrecords (UniqueID,rentalID,renterID) VALUES ('$test','$rentalID','$ID')";

                $results = mysqli_query($conn, $c);
                if ($results === FALSE) {
                    echo " < p>Unable to add product to rental records . Error code " . mysqli_errno($conn) . ": " . mysqli_error($conn) . " </p > \n";
                } else {
                    $_SESSION['success'] = "Product Rented!";
                    header('Location: clientHome.php');
                }
            }
        }
    }
}

//Functions for calculations relating to rent costs
class Calculations
{
    //calculate rent estimate from rental length
    function calculateRental($days)
    {
        $date = strtotime(date('Y-m-d'));
        $date = strtotime("+$days day", $date);
        echo "Rental End Date: " . date('d/m/Y', $date);
        echo "<br>Estimated Cost: $" . $_GET['CPD'] * $days . " (+$" . $_GET['COD'] . " per day overdue)";
    }
    //calculate return cost from rental length
    function calculateReturn()
    {
        global $conn;

        $UID = $_GET['UID'];
        $CPD = $_GET['CPD'];
        $COD = $_GET['COD'];


        $a = "SELECT rentedDate,dueDate FROM rented WHERE rented.UniqueID='$UID'";
        $results = mysqli_query($conn, $a);
        if ($results === FALSE) {
            echo " < p>Unable to get products rental date information . Error code " . mysqli_errno($conn) . ": " . mysqli_error($conn) . " </p > \n";
        } else {
            $test = mysqli_fetch_assoc($results);
            $date = strtotime(date('Y-m-d'));
            $rentedDate = strtotime($test['rentedDate']);

            $dueDate = strtotime($test['dueDate']);

            // Get the difference and divide into
            // total no. seconds 60/60/24 to get number of days
            $differenceOfDays = ($date - $rentedDate) / 60 / 60 / 24;
            if ($differenceOfDays == 0) {
                $differenceOfDays = 1;
            }

            $overdueCheck = ($date - $dueDate) / 60 / 60 / 24;
            if ($overdueCheck < 0) {
                $overdueCheck = 0;
            }

            if ($overdueCheck > 0) {
                echo "<br><br> THIS PRODUCT IS " . $overdueCheck . " DAYS OVERDUE";
                $regularCost = ($CPD * $differenceOfDays) - ($CPD * $overdueCheck);
                $overDueCost = $COD * $overdueCheck;
                $cost = $regularCost + $overDueCost;
                echo "<br><br>COST TO RETURN NOW: $" . $cost;
            } else {
                echo "<br><br>This product is on time. <br>due date: " . date('d-m-Y', $dueDate);
                $cost = $CPD * $differenceOfDays;
                echo "<br><br>COST TO RETURN NOW: $" . $cost;
            }
        }
    }
}

//Error Function
class Errors
{
    function display_error()
    {
        global $errors;

        if (count($errors) > 0) {
            echo '<div class="error">';
            foreach ($errors as $error) {
                echo $error . '<br>';
            }
            echo '</div>';
        }
    }
}
