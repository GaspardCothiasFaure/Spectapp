<?php

// Inclusion of connect-db file to be connected to the database and definition of the character encoding format
include('connect-db.php');
mysqli_set_charset($link_db, "utf8");
setlocale(LC_TIME, 'fr_FR.utf8','fra');
date_default_timezone_set('Europe/Paris');

//Verify the POST data sent by booking-confirmation.js
if (isset($_POST)) {

  $reservation_code=$_POST['reservation_code'];
  $performance_id=$_POST['performance_id'];
  $reserved_seats=$_POST['reserved_seats'];
  
  // Prevent from SQL and XSS injections :
  $client_email = mysqli_real_escape_string($link_db,htmlspecialchars($_POST['client_email']));

  // Create new reservation in reservation table
  mysqli_query($link_db, "INSERT INTO `reservation` (`reservation_code`, `client_email`, `performance_id`, `reserved_seats`) VALUES ('".$reservation_code."', '".$client_email."', '".$performance_id."', '".$reserved_seats."');");

  // Add the new reserved seats for the performance in the show table
  if ($result_query = mysqli_query($link_db, "SELECT `performance_reserved_seats` FROM `performance` WHERE `performance_id`=".$performance_id)){
    $performance_row_assoc = mysqli_fetch_assoc($result_query);
    $performance_reserved_seats=$performance_row_assoc["performance_reserved_seats"];

    if($performance_reserved_seats!="")  $performance_reserved_seats.=",".$reserved_seats;
    else  $performance_reserved_seats=$reserved_seats;
    
    mysqli_query($link_db, "UPDATE `performance` SET `performance_reserved_seats` ='".$performance_reserved_seats."' WHERE `performance_id`=".$performance_id);

  }
  else  header('Location: ../error/error.html');

}
else  header('Location: ../error/error.html');

?>