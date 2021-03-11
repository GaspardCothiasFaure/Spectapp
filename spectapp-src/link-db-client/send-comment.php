<?php

// Inclusion of connect-db file to be connected to the database and definition of the character encoding format
include('connect-db.php');
mysqli_set_charset($link_db, "utf8");
setlocale(LC_TIME, 'fr_FR.utf8','fra');
date_default_timezone_set('Europe/Paris');

// Verify the POST data send by show_critics.php
if (isset($_POST['comment']) && isset($_POST['comment_email']) && isset($_POST['show_id']) && isset($_POST['comment_rate'])) {

    $comment=addslashes($_POST['comment']);
    $comment_email=$_POST['comment_email'];
    $show_id=$_POST['show_id'];
    $comment_rate=$_POST['comment_rate'];

    // Prevent from SQL and XSS injections :
    $client_email = mysqli_real_escape_string($link_db,htmlspecialchars($_POST['comment_email']));
    $client_email = mysqli_real_escape_string($link_db,htmlspecialchars($_POST['comment']));    
  
    // Insert the critic in the show-critic table
    // Verify the query went well
    if (mysqli_query($link_db,"INSERT INTO `show-critic` (`comment_id`, `comment`, `show_id`, `comment_date`, `comment_email`, `comment_rate`) VALUES (NULL, '".$comment."', '".$show_id."', '".date("Y-m-d\TH:i")."', '".$comment_email."', '".$comment_rate."');")){
        
        // Redirection to the same page
        header('Location:../show-critics.php?show_id='.$show_id);
    }
    else    header('Location:../error/error.html');
}
else    header('Location:../error/error.html');

?>