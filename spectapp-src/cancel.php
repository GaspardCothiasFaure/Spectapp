<!DOCTYPE html>
<html lang="fr" class="h-100">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="icon/icon.ico">
        <title>Spectapp</title>
        <link rel="stylesheet" href="css/bootstrap/bootstrap.css"/>     <!-- Bootstrap CSS link -->
        <link rel="stylesheet" href="css/toastr/toastr.min.css">        <!-- Toastr CSS -->
        <link rel="stylesheet" href="css/spectapp-style.css"/>          <!-- Global app CSS -->
        <link rel="stylesheet" href="css/cancel.css"/>                  <!-- Individual CSS -->
    </head>
    <body class="d-flex flex-column h-100">

        <header>

            <nav class="navbar navbar-expand-lg navbar-light">
                <a class="navbar-brand" href="index.php">Spectapp</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Réservation</a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link" href="cancel-portal.html">Annulation</a>
                        </li>
                    </ul>
                </div>
            </nav>

        </header>

        <main role="main" class="container mt-4 mb-4">

            <?php

            // Inclusion of connect-db file to be connected to the database and definition of the character encoding format and local time zone
            include('link-db-client/connect-db.php');
            mysqli_set_charset($link_db, "utf8");
            setlocale(LC_TIME, 'fr_FR.utf8','fra');

            // Verify connection to database
            if($link_db){

                // Start session
                session_start();

                // Verify reservation code receiving
                if(isset($_SESSION['reservation_code'])){

                    // Verify reservation code format
                    if($_SESSION['reservation_code'] !== ""){
                        $reservation_code = $_SESSION['reservation_code'];

                        // Retreive information about the reservation from the database
                        // Verify the query went well
                        if ($result_query = mysqli_query($link_db,"SELECT * FROM `reservation` WHERE `reservation_code`='".$reservation_code."'")) {
                            $resa_row_assoc = mysqli_fetch_assoc($result_query);

                            // Get information about the reservation
                            $performance_id=$resa_row_assoc['performance_id'];
                            $client_email=$resa_row_assoc['client_email'];
                            $reserved_seats_string=$resa_row_assoc["reserved_seats"];
                            $nb_seats_reserved=count(preg_split('/,/',$reserved_seats_string));
                            $seats_reserved_array=preg_split('/,/',$reserved_seats_string);
                        }
                        else    header('Location:error/error.html');

                        // Retreive information about the performance from the database
                        // Verify the query went well
                        if ($result_query = mysqli_query($link_db,"SELECT * FROM `performance` WHERE `performance_id`='".$performance_id."'")) {
                            $performance_row_assoc = mysqli_fetch_assoc($result_query);

                            // Get information about the performance
                            $show_id=$performance_row_assoc["show_id"];
                            $performance_date=$performance_row_assoc["performance_date"];
                            $performance_date=strftime("%A %e %B %G à %Hh%M",strtotime($performance_date));
                            $performance_date=str_replace('Ã','à',utf8_encode(ucfirst($performance_date)));

                            // Retreive information about the show from the database
                            // Verify the query went well
                            if ($result_query = mysqli_query($link_db,"SELECT * FROM `show` WHERE `show_id`='".$show_id."'")) {
                                $show_row_assoc = mysqli_fetch_assoc($result_query);

                                // Get information about the show
                                $show_name=$show_row_assoc["show_name"];
                                $show_artist=$show_row_assoc["show_artist"];
                                $poster_path="posters/".$show_row_assoc["show_poster_file"];
                            }
                            else    header('Location:error/error.html');
                        }
                        else    header('Location:error/error.html'); 
                    }
                    else    header('Location:error/error.html');
                }
                else    header('Location:error/error.html');
            }
            else    header('Location:error/error.html');

            ?>

            <div>

                <div class='card mb-3'>
                    <div class='row no-gutters'>
                        <div class='col-md-4'>
                            <img src='<?php echo $poster_path; ?>' class='performance-info-poster card-img w-75' alt='poster'>
                        </div>
                        <div class='col-md-8'>
                            <div class='card-body'>
                                <h5 class='card-title'><?php echo $show_name; ?></h5>
                                <p class='card-text'><?php echo $show_artist; ?></p>
                                <p class='card-text'><?php echo $performance_date; ?></p>
                                <p class='card-text font-weight-light mb-0'>Nombre de places réservées : <span class="font-weight-bold"><?php echo $nb_seats_reserved; ?></span></p>
                                <small class="text-muted">Numéro(s) de(s) place(s) reservée(s) : <?php echo $reserved_seats_string; ?></small>
                                <p class='card-text font-weight-light mt-3'>Code de réservation : <span class="font-weight-bold"><?php echo $reservation_code; ?></span></p>
                                <p class='card-text font-weight-light'>Email de reservation : <span class="font-weight-bold"><?php echo $client_email; ?></span></p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="container mb-3 d-flex justify-content-center">

                <div id="cancelation-confirmation-container" class="card text-center">
                    <div class="card-body">
                        <p class="card-title">Annuler cette réservation ?</p>
                        <a id="cancelation-confirmation-btn" href='cancel.php?cancelation=true' class="btn btn-warning"><span>Annuler</span></a>
                    </div>
                </div>

                <div class="text-center">
                
                    <?php
                    
                    // Verify receiving of cancelation GET parameter
                    if (isset($_GET['cancelation'])) {

                        // Display confirmation of the cancelation
                        if($_GET['cancelation']==true) {

                            // Close session
                            session_unset();

                            // Delete the reservation in reservation table
                            $result_q1 = mysqli_query($link_db,"DELETE FROM `reservation` WHERE `reservation_code`='".$reservation_code."'");

                            // Retreive the performance information to update the reserved seats
                            if ($result_query = mysqli_query($link_db, "SELECT `performance_reserved_seats` FROM `performance` WHERE `performance_id`=".$performance_id)){
                                $performance_row_assoc = mysqli_fetch_assoc($result_query);
                                
                                $str_performance_reserved_seats=$performance_row_assoc["performance_reserved_seats"];
                                $performance_reserved_seats_array=preg_split('/,/',$str_performance_reserved_seats);
                                $new_performance_reserved_seats=array_diff($performance_reserved_seats_array,$seats_reserved_array);
                                $new_performance_reserved_seats=implode(",",$new_performance_reserved_seats);
                                
                                // Delete the reserved seats in the performance (update performance_reserved_seats)
                                $result_q2 =mysqli_query($link_db, "UPDATE `performance` SET `performance_reserved_seats` ='".$new_performance_reserved_seats."' WHERE `performance_id`=".$performance_id);
                            }
                            else    header('Location:error/error.html');

                            // Verify the two delete queries went well
                            if ($result_q1 && $result_q2) {
                                echo '<p>Réservation annulée.</p>';
                                echo '<a href="index.php">Revenir à l\'acceuil</a>';
                            }
                            else    header('Location:error/error.html');
                        }
                    }

                    ?>

                </div>
            </div>

        </main>

        <footer class="footer mt-auto py-2">
            <div class="footer-copyright text-center text-white">
                <a class="text-white" href="index.php">Spectapp</a>
            </div>
        </footer>

        <script src="js/jquery/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>                               <!-- Link to the JQuery JavaScript library -->
        <script src="js/bootstrap/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>    <!-- Link to the Bootstrap JavaScript library -->
        <script src="js/toastr/toastr.min.js"></script>                                                                                                                             <!-- Link to the Toastr JavaScript file -->
        <script src="js/cancel.js"></script>                                                                                                                                        <!-- Link to the individual JavaScript file -->
    </body>
</html>