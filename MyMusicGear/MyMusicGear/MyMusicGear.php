<?php
//This is the main page of the dynamic site, the following checks follow the users decisions and include the relevant pages.
session_start();

if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['user']);
    header("location: MyMusicGear.php");
}

if(empty($_SESSION['entry']) && !isset($_GET['entry'])) {
    include('login.php');
}

if(isset($_GET['entry'])) {
    include ('register.php');
}

If(isset($_SESSION['Login'])){
    switch($_SESSION['Login']){
        case 'admin':
            include ('home.php');
            unset($_SESSION['Login']);
            break;
        case 'client':
            include ('clientHome.php');
            unset($_SESSION['Login']);

    }
}

if(isset($_GET['home'])) {
    switch($_GET['home']) {
        case 1:
            include('home.php');
            break;
        case 0:
            include('clientHome.php');
            break;
    }
}


if (isset($_POST["function"])) {
    switch ($_POST["function"]) {
        case 'Add':
            include('addProduct.php');
            break;
        case 'search':
            include('search.php');
            break;
        case 'list':
        case 'view':
        case 'hist':
        case 'renting':
            include('listProducts.php');
            break;

    }
}


if (isset($_GET['setAvailable'])) {
    include('listProducts.php');
}

if (isset($_GET['setRent'])) {
    switch ($_GET['setRent']) {
        case 0:
            include('rentProduct.php');
            break;
        case 1:
            include('returnProduct.php');

    }
}

?>