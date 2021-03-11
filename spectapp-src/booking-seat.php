<!DOCTYPE html>
<html lang="fr" class="h-100">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Spectapp</title>
        <link rel="icon" href="icon/icon.ico">
        <link rel="stylesheet" href="css/bootstrap/bootstrap.css"/>     <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="css/toastr/toastr.min.css">        <!-- Toastr CSS -->
        <link rel="stylesheet" href="css/spectapp-style.css"/>          <!-- Global app CSS -->
        <link rel="stylesheet" href="css/booking-seat.css"/>            <!-- Individual CSS -->
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
                        <li class="nav-item active">
                            <a class="nav-link" href="index.php">Réservation</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="cancel-portal.html">Annulation</a>
                        </li>
                    </ul>
                </div>
            </nav>

        </header>

        <main role="main" class="container">

            <?php

            // Inclusion of connect-db file to be connected to the database, definition of the character encoding format and local time zone
            include('link-db-client/connect-db.php');
            mysqli_set_charset($link_db, "utf8");
            setlocale(LC_TIME, 'fr_FR.UTF-8','fra');

            // Verify connection to database
            if($link_db){

                // Define number of seats according to covid code
                $nb_seats_tot_cc0=77;
                $nb_seats_tot_cc1=49;
                    
                // Verify receiving of performance id
                if(isset($_GET['performance_id'])) {
                    $performance_id=$_GET['performance_id'];

                    // Retrieve and display information on the performance selected at booking-date.php, display COVID information and left seats
                    // Verify the query went well
                    if ($result_query = mysqli_query($link_db, "SELECT * FROM `performance` WHERE `performance_id`=".$performance_id)) {
                        $performnance_row_assoc = mysqli_fetch_assoc($result_query);

                        $show_id=$performnance_row_assoc["show_id"];

                        // Retrieve and diplay information on the show selected
                        // Verify the query went well
                        if ($result_query = mysqli_query($link_db, "SELECT * FROM `show` WHERE `show_id`=".$show_id)) {
                            $show_row_assoc = mysqli_fetch_assoc($result_query);

                            $show_name=$show_row_assoc["show_name"];
                            $show_artist=$show_row_assoc["show_artist"];
                            $performance_covid_code=$performnance_row_assoc["performance_covid_code"];
                            $show_description=$show_row_assoc["show_description"];
                            $poster_path="posters/".$show_row_assoc["show_poster_file"];
                            $performance_reserved_seats=$performnance_row_assoc["performance_reserved_seats"];

                            $performance_date=$performnance_row_assoc["performance_date"];
                            $performance_date=strftime("%A %e %B %G à%Hh%M",strtotime($performance_date));
                            $performance_date=str_replace('Ã','à',utf8_encode(ucfirst($performance_date)));

                            // In case of COVID19 protocol, display a warning and set number free seats
                            if ($performance_covid_code==1) {
                                if(preg_split('/,/',$performance_reserved_seats)[0]=="")    $nb_seats_left=$nb_seats_tot_cc1;
                                else    $nb_seats_left=$nb_seats_tot_cc1-count(preg_split('/,/',$performance_reserved_seats));

                                $covid_performance_resume="<span class='font-weight-bolder text-warning'>Attention protocole COVID19 : </span>En raison de mesures de distinction sociale dues à la pandémie de COVID19, un siège sur deux est reservable lors de cet évenement.";
                            }
                            else{
                                if(preg_split('/,/',$performance_reserved_seats)[0]=="")    $nb_seats_left=$nb_seats_tot_cc0;
                                else    $nb_seats_left=$nb_seats_tot_cc0-count(preg_split('/,/',$performance_reserved_seats));

                                $covid_performance_resume=null;
                            }
                        }
                        else    header('Location:error/error.html');
                    }
                    else    header('Location:error/error.html');
                }
                else    header('Location:error/error.html');
            }
            else    header('Location:error/error.html');

            ?>

            <div class="mt-3">
                <div class='card mb-3'>
                    <div class='row no-gutters'>
                        <div class='col-md-4'>
                            <img src='<?php echo $poster_path; ?>' class='card-img performance-info-poster' alt='poster'>
                        </div>
                        <div class='col-md-8'>
                            <div class='card-body'>
                                <h5 class='card-title'><?php echo $show_name; ?></h5>
                                <p class='card-text'> <?php echo $show_artist; ?></p>
                                <p class='card-text'><?php echo $performance_date; ?></p>
                                <p class='card-text font-weight-light'>Nombre de places restantes : <span id="nb-seats-left-span" class="font-weight-bold"><?php echo $nb_seats_left; ?></span></p>
                                <p class='card-text font-weight-light mb-0'>Nombre de places selectionnées : <span id="nb-seats-selected-span" class="font-weight-bold">0</span></p>
                                <small class="text-muted mb-0">Nombre maximum de places reservables : 5</small>
                                <p class="mt-3 mb-0"><?php echo $covid_performance_resume ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3 mb-3">
                <h3 >Choisissez vos places</h5>
            </div>

            <hr class="mb-3 mt-1">

            <div class="d-flex flex-row justify-content-center mb-3 flex-wrap">

                <div id="map-container" class="map-container mb-3">
                    <div class="scene"><p id="scene-caption">Scène</p></div>
                    <div class="map"></div> 
                </div>

                <div class="legend-scale mt-3 ml-4">
                    <ul class='legend-labels'>
                        <li><span class="green"></span>Siège libre</li>
                        <li><span class="blue"></span>Siège selectionné</li>
                        <li><span class="red"></span>Siège occupé</li>
                        <?php if ($performance_covid_code==1) echo "<li><span class='grey'></span>Siège non reservable</li>";?>
                    </ul>
                </div>

            </div>

            <div class="mb-5 mt-5 text-center">
                <button id="next-step" class="btn btn-primary w-50">Valider ces places</button>
            </div>

        </main>

        <footer class="footer mt-auto py-2">
            <div class="footer-copyright text-center text-white">
                <a class="text-white" href="index.php">Spectapp</a>
            </div>
        </footer>

        <script src="js/jquery/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>                               <!-- Link to the JQuery JavaScript library -->
        <script src="js/bootstrap/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>    <!-- Link to the Bootstrap JavaScript library -->
        <script src="js/toastr/toastr.min.js"></script>                                                                                                                             <!-- Link to the Toastr JavaScript library -->
        <script src="js/booking-seat.js"></script>                                                                                                                                  <!-- Link to the individual JavaScript file -->
    </body>
</html>