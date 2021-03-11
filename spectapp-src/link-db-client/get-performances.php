<?php

// Inclusion of connect-db file to be connected to the database and definition of the character encoding format
include('connect-db.php');
mysqli_set_charset($link_db, "utf8");
setlocale(LC_TIME, 'fr_FR.utf8','fra');
date_default_timezone_set('Europe/Paris');

// Fetch the performance table and send it in json
if ($result_query = mysqli_query($link_db, "SELECT * FROM `performance`")) {
  while ($row = mysqli_fetch_assoc($result_query)) {
    $tab_assoc[]=$row;
  }
}
echo json_encode($tab_assoc);
?>