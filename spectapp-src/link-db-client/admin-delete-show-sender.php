<?php

// Inclusion of connect-db file to be connected to the database and definition of the character encoding format
include('connect-db.php');
mysqli_set_charset($link_db, "utf8");
setlocale(LC_TIME, 'fr_FR.utf8','fra');
date_default_timezone_set('Europe/Paris');

// Fetch the POST data sent by admin-delete
if (isset($_POST["show_id"])) {
    $show_id=$_POST["show_id"];

    //Delete show, performances, reservations and poster file
    $resultQ1 = mysqli_query($link_db,"SELECT `show_poster_file` FROM `show` WHERE `show_id`='".$show_id."';");
    $response = mysqli_fetch_array($resultQ1);
    $fileToDel = $response['show_poster_file'];
    $resultQ2 =unlink("../posters/".$fileToDel);

    $resultQ3 = mysqli_query($link_db,"DELETE FROM `show-critic` WHERE `show_id`='".$show_id."'");
    $resultQ4 = mysqli_query($link_db,"DELETE FROM `reservation` WHERE `performance_id` IN (SELECT `performance_id` FROM `performance` WHERE `show_id`='".$show_id."'");
    $resultQ5 = mysqli_query($link_db,"DELETE FROM `performance` WHERE `show_id`='".$show_id."'");
    $resultQ6 = mysqli_query($link_db,"DELETE FROM `show` WHERE `show_id`='".$show_id."'");

    //Verify all the queries went well
    if ($resultQ1 && $resultQ2 && $resultQ3 && $resultQ5)   header("Location:../admin/admin-delete.php?del=done");
    else    header('Location:../error/error.html');
}
else    header('Location:../error/error.html');

?>