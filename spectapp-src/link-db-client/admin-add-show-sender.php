<?php

// Inclusion of connect-db file to be connected to the database and definition of the character encoding format and local time zone
include('connect-db.php');
mysqli_set_charset($link_db, "utf8");
setlocale(LC_TIME, 'fr_FR.utf8','fra');
date_default_timezone_set('Europe/Paris');

// Verify reservation code receiving
if( isset($_POST["show_name"])  && isset($_POST["show_artist"]) && isset($_POST["show_description"]) && isset($_FILES["show_posterFile"]["name"]) && isset($_POST["showDates"])  && isset($_POST["covidProtocol"])){

    $show_name=addslashes($_POST["show_name"]);
    $show_description=addslashes($_POST["show_description"]);
    $show_artist=addslashes($_POST["show_artist"]);

    $showDates=$_POST["showDates"];
    $covidProtocol=$_POST["covidProtocol"];
    
    $dates_nb=count($showDates);
    $nb_equal_values=count(array_unique($showDates));
    if ($nb_equal_values!=count($showDates))    header('Location: ../admin/admin-add.html?error=i');
    else {
        // File upload path
        $targetDir = "../posters/";
        $fileName = basename($_FILES["show_posterFile"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

        // Allow certain file formats
        $allowTypes = array('jpg','png','jpeg','JPG','PNG','JPEG');
        if(in_array($fileType, $allowTypes)){
            // Upload file to server
            if(move_uploaded_file($_FILES["show_posterFile"]["tmp_name"], $targetFilePath)){

                //Recuperation of the show id 
                if ($result_query = mysqli_query($link_db,"SELECT MAX(`show_id`) FROM `show`")) {
                    $show_row_assoc = mysqli_fetch_assoc($result_query);

                    $show_id=intval($show_row_assoc['MAX(`show_id`)'])+1;
                }
                else   header('Location: ../error/error.html');

                //Recuperation of the show id 
                if ($result_query = mysqli_query($link_db,"SELECT MAX(`performance_id`) FROM `performance`")) {
                    $show_row_assoc = mysqli_fetch_assoc($result_query);

                    $performance_id=intval($show_row_assoc['MAX(`performance_id`)']);
                }
                else   header('Location: ../error/error.html');

                //Add informations in show table :
                $insert_show = $link_db->query("INSERT INTO `show` (`show_name`, `show_poster_file`, `show_id`, `show_description`, `show_artist`) VALUES ('".$show_name."', '".$fileName."','".$show_id."', '".$show_description."', '".$show_artist."')");

                //Add informations in performance table :
                for ($i=0; $i < $dates_nb; $i++)
                {
                    $performance_id=$performance_id+1;
                    $insert_performance = $link_db->query("INSERT INTO `performance` (`performance_id`, `performance_date`, `performance_covid_code`, `performance_reserved_seats`, `show_id`) VALUES ('".$performance_id."', '".$showDates[$i]."', '".$covidProtocol[$i]."','','".$show_id."')");
                }
                if($insert_show && $insert_performance){
                    header('Location: ../admin/admin-add.html?newshow=t');
                }
                else   header('Location: ../error/error.html');
            }
            else   header('Location: ../admin/admin-add.html?error=f');
        }
        else     header('Location: ../error/error.html');
    }
}
else    header('Location: ../error/error.html');

?>