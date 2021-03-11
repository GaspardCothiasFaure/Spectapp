<?php

// Inclusion of connect-db file to be connected to the database and definition of the character encoding format
include('connect-db.php');
mysqli_set_charset($link_db, "utf8");
setlocale(LC_TIME, 'fr_FR.utf8','fra');
date_default_timezone_set('Europe/Paris');

//Start session
session_start();

//Fetch POST data send by admin-portal.html
if(isset($_POST['user']) && isset($_POST['password']) && isset($_POST['admin-choice'])){
    
    // Prevent from SQL and XSS injections :
    $user = mysqli_real_escape_string($link_db,htmlspecialchars($_POST['user'])); 
    $password = mysqli_real_escape_string($link_db,htmlspecialchars($_POST['password']));
    
    // Verify that the admin account exists in the database
    $query = "SELECT count(*) FROM `admin-user` WHERE user = '".$user."' AND password = '".$password."' ";
    $exec_query = mysqli_query($link_db,$query);
    $response = mysqli_fetch_array($exec_query);
    $count = $response['count(*)'];
    if($count!=0)
    {
        $_SESSION['user'] = $user;
        if ($_POST['admin-choice']=="add")   header('Location: ../admin/admin-add.html');
        else    header('Location: ../admin/admin-delete.php');
    }
    else    header('Location: ../admin/admin-portal.html?error=notfind');
}
else    header('Location: ../error/error.html');

?>