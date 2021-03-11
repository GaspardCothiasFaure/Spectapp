<!DOCTYPE html>
<html lang="fr" class="h-100">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Spectapp</title>
        <link rel="icon" href="icon/icon.ico">
        <link rel="stylesheet" href="css/bootstrap/bootstrap.css"/>     <!-- Bootstrap CSS -->
        <link href='js/fullcalendar/main.min.css' rel='stylesheet' />   <!-- Full calendar CSS -->
        <link rel="stylesheet" href="css/spectapp-style.css"/>          <!-- Global app CSS -->
        <link rel="stylesheet" href="css/booking-date.css"/>            <!-- Individual CSS -->
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
                            <a class="nav-link" href="index.php">Réservation</span></a>
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

            // Inclusion of connect-db file to be connected to the database, definition of the character encoding format and definition of the local time zone
            include('link-db-client/connect-db.php');
            mysqli_set_charset($link_db, "utf8");
            setlocale(LC_TIME, 'fr_FR.utf8','fra');

            // Verify connection to database
            if($link_db){

                // Verify receiving of performance id
                if(isset($_GET['show_id'])) {
                
                    $show_id=$_GET['show_id'];
                    $covid_performance_resume="";

                    // Retrieving information on the show selected at index.php in the database
                    // Verify the query went well
                    if ($result_query = mysqli_query($link_db, "SELECT * FROM `show` WHERE `show_id`=".$show_id)) {
                        $shows_tab_assoc = mysqli_fetch_assoc($result_query);

                        $show_poster_file_path="posters/".$shows_tab_assoc["show_poster_file"];
                        $show_name=$shows_tab_assoc["show_name"];
                        $show_artist=$shows_tab_assoc["show_artist"];
                        $show_description=$shows_tab_assoc["show_description"];

                        // Retrieving information on the performances of the show
                        // Verify the query went well
                        if ($result_query = mysqli_query($link_db, "SELECT * FROM `performance` WHERE `performance_covid_code`=1 AND `show_id`=".$show_id)) {
                            if (mysqli_num_rows($result_query)>0){
                                while ($result_row = mysqli_fetch_assoc($result_query)) {
                                    $covid_performance_tab_assoc[]=$result_row;
                                }
    
                                $covid_protocol_performance_count=count($covid_performance_tab_assoc);
    
                                // If there is one (or several) COVID19 protocol (performance_covid_code=1) on the different performances of the show, inform the user
                                if($covid_protocol_performance_count==1){
                                    $date_covid_performance=$covid_performance_tab_assoc[0]["performance_date"];
                                    $date_covid_performance=strftime("%A %e %B %G à %Hh%M",strtotime($date_covid_performance));
                                    $date_covid_performance=str_replace('Ã','à',utf8_encode($date_covid_performance));
                                    $covid_performance_resume="<p id='covid-code'> <span class='font-weight-bolder text-warning'>Attention protocole COVID19 : </span>La date du ".$date_covid_performance." se déroulera dans des conditions strictes de distinction sociale.</span></p>";
    
                                }
                                else if ($covid_protocol_performance_count>1){
    
                                    $dates_covid_performances_array=array();
    
                                    foreach ($covid_performance_tab_assoc as $covid_show_assoc) {
                                        $date_covid_performance=strftime("%A %e %B %G à %Hh%M",strtotime($covid_show_assoc["performance_date"]));
                                        array_push($dates_covid_performances_array,$date_covid_performance);
                                    }
    
                                    $covid_performance_resume="<p id='covid-code'> <span class='font-weight-bolder text-warning'>Attention protocole COVID19 : </span>Les dates du ".implode($date_covid_performance)." se déroulera dans des conditions strictes de distinction sociale.</span></p>";
    
                                }
                                else    $covid_performance_resume=null;
                            }
                            else    $covid_performance_resume=null;
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
                            <img src='<?php echo $show_poster_file_path; ?>' class='card-img performance-info-poster' alt='poster'>
                        </div>
                        <div class='col-md-8'>
                            <div class='card-body'>
                                <h5 class='card-title'><?php echo $show_name; ?></h5>
                                <p class='card-text'> <?php echo $show_artist; ?></p>
                                <p id="show-decription" class='card-text'> <?php echo $show_description; ?></p>
                                <p class='card-text'> <?php echo $covid_performance_resume; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-3 mb-3">
                <h3 >Choisissez votre date</h5>
            </div>

            <hr class="mb-3 mt-1">

            <div id='calendar-container' class="mb-5">
                <div id="calendar" class="p-1"></div>

                <div id='legend'>
                    <ul class='inlineList'>
                        <li><span class="green"></span>Date libre</li>
                        <li><span class="yellow"></span>Date avec un protocole COVID 19</li>
                        <li><span class="red"></span>Date complète</li>
                    </ul>
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
        <script src='js/fullcalendar/main.min.js'></script>                                                                                                                         <!-- Link to the Bootstrap JavaScript library -->
        <script src='js/fullcalendar/locales-all.min.js'></script>                                                                                                                  <!-- Link to the Bootstrap JavaScript library -->
        <script src="js/booking-date.js"></script>                                                                                                                                  <!-- Link to the Individual JavaScript file -->
    </body>
</html>