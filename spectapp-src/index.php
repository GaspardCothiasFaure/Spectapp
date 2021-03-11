<!DOCTYPE html>
<html lang="fr" class="h-100">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Spectapp</title>
        <link rel="icon" href="icon/icon.ico"/>
        <link rel="stylesheet" href="css/bootstrap/bootstrap.css"/>                 <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css">    <!-- Font-awesome CSS -->
        <link rel="stylesheet" href="css/index.css"/>                               <!-- Individual CSS -->
    </head>
    <body class="d-flex flex-column h-100">

        <header>

            <nav class="navbar navbar-expand-lg navbar-light">
                <a class="navbar-brand" href="./index.php">Spectapp</a>
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

        <main role="main" class="container mt-5 mb-5">

            <div class="text-center mb-4">
                <h1 class="h3 mb-3 bold">Spectapp - Reservation</h1>
                <h4 class="h4 mb-1 font-weight-normal">Choisissez votre spectacle</h4>
                <a href="show-search.html">Ou rechercher un spectacle</a>
            </div>

            <div class="flex-wrap d-flex justify-content-between">

                <?php                

                // Inclusion of connect-db file to be connected to the database and definition of the character encoding format
                include('link-db-client/connect-db.php');
                mysqli_set_charset($link_db, "utf8");
                setlocale(LC_TIME, 'fr_FR.utf8','fra');

                /*  Display the show note on colored stars by id of the show
                *  @param $show_id : id of the show
                *  @param $link_db : link to database
                *  @return HTML code of the stars
                */
                function writeRate($show_id,$link_db) {
                    
                    if ($result_query = mysqli_query($link_db, "SELECT ROUND(AVG(`comment_rate`)) FROM `show-critic` WHERE `show_id`=".$show_id)) {
                        $response      = mysqli_fetch_array($result_query);
                        $count = $response['ROUND(AVG(`comment_rate`))'];
                        if ($count==0) {
                            return "";
                        }
                        else {
                            $res="";
                            for ($i=0; $i < 5; $i++) {
                                if ($count>0)   $res=$res."<span class='fa fa-star checked'></span>";
                                else    $res=$res."<span class='fa fa-star'></span>";
                                $count--;
                            }
                            return $res;
                        }
                    }
                    else    header('Location:error/error.html');
                }

                // Verify connection to database
                if ($link_db) {

                    // Retrieving of the 'show' table to display the different shows open for booking
                    // Verify the query went well
                    if ($result_query = mysqli_query($link_db, "SELECT * FROM `show` GROUP BY `show_name`")) {
                        while ($result_row = mysqli_fetch_assoc($result_query)) {
                            $shows_tab_assoc[]=$result_row;
                        }

                        // Loop on each show row
                        foreach ($shows_tab_assoc as $show_row_assoc) {

                            $show_name=$show_row_assoc["show_name"];
                            $show_id=$show_row_assoc["show_id"];
                            $show_artist=$show_row_assoc["show_artist"];
                            $show_poster_file_path="posters/".$show_row_assoc["show_poster_file"];
                            $show_description=$show_row_assoc["show_description"];

                            // Get date interval between the first and last performance
                            // Verify the query went well
                            if ($result_query = mysqli_query($link_db,"SELECT `performance_date` FROM `performance` WHERE `show_id`=".$show_id)) {
                                
                                // Fetch row(s) of the query
                                while ($result_row = mysqli_fetch_assoc($result_query)) {
                                    $performance_dates_array[]=$result_row["performance_date"];
                                }

                                $min_date_performance=strftime("%d/%m/%Y",strtotime(min($performance_dates_array)));
                                $max_date_performance=strftime("%d/%m/%Y",strtotime(max($performance_dates_array)));
                                $performance_dates="Du ".$min_date_performance." au ".$max_date_performance;

                                // Display the show item with information on the show and link to next booking step
                                echo "
                                <div class='card mb-3'>
                                    <div class='row no-gutters'>
                                        <div class='col-md-4'>
                                            <img src='".$show_poster_file_path."' class='card-img' alt='poster'>
                                        </div>
                                        <div class='col-md-8'>
                                            <div class='card-body'>
                                                <h5 class='card-title'>".$show_name."</h5>
                                                <p class='card-text mb-2'>".$show_artist."</p>
                                                ".writeRate($show_id,$link_db)."
                                                <p class='card-text small-text  mt-2'>".$show_description."</p>
                                                <p class='card-text'><small class='text-muted'>".$performance_dates."</small></p>
                                                <a class='badge badge-primary' href=\"booking-date.php?show_id=".$show_id."\">Réserver</a>
                                                <a class='badge badge-info' href=\"show-critics.php?show_id=".$show_id."\">Voir les avis</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                ";
                            }
                            else    header('Location:error/error.html');
                            $performance_dates_array=array();
                        }
                    }
                    else    header('Location:error/error.html');
                }
                else    header('Location:error/error.html');

                ?>

            </div>

            <h4 class=" text-center h4 mb-2 mt-3 font-weight-normal">Prix unique : 5 € la place.</h4>

        </main>

        <footer class="footer mt-auto py-2">
            <div class="footer-copyright text-center text-white">
                <a class="text-white" href="index.php">Spectapp</a>
            </div>
        </footer>

        <script src="js/jquery/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>                               <!-- Link to the JQuery JavaScript library -->
        <script src="js/bootstrap/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>    <!-- Link to the Bootstrap JavaScript library -->
    </body>
</html>
