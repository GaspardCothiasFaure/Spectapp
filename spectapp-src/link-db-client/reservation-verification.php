<?php

// Inclusion of connect-db file to be connected to the database and definition of the character encoding format
include('connect-db.php');
mysqli_set_charset($link_db, "utf8");
setlocale(LC_TIME, 'fr_FR.utf8','fra');
date_default_timezone_set('Europe/Paris');

// Start session
session_start();

// Verify the POST data send by booking-confirmation.js
if(isset($_POST['reservation_code']) && isset($_POST['client_email'])){
   
   // Prevent from SQL and XSS injections :
   $reservation_code = mysqli_real_escape_string($link_db,htmlspecialchars($_POST['reservation_code'])); 
   $client_email = mysqli_real_escape_string($link_db,htmlspecialchars($_POST['client_email']));
   
   // Verify that the reservation exists in the database
   $query = "SELECT count(*) FROM `reservation` WHERE reservation_code = '".$reservation_code."' AND client_email = '".$client_email."' ";
   $exec_query = mysqli_query($link_db,$query);
   $response = mysqli_fetch_array($exec_query);
   $count = $response['count(*)'];
   if($count!=0)
   {
      $_SESSION['reservation_code'] = $reservation_code;
      header('Location: ../cancel.php');
   }
   else  header('Location: ../cancel-portal.html?error=notfind');
}
else  header('Location: ../error/error.html');

?>