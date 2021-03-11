<!DOCTYPE html>
<html lang="fr" class="h-100">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="../icon/icon.ico">
        <title>Spectapp</title>
        <link rel="stylesheet" href="../css/bootstrap/bootstrap.css"/>  <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="../css/toastr/toastr.min.css">     <!-- Toastr CSS -->
        <link rel="stylesheet" href="../css/spectapp-style.css"/>       <!-- Global app CSS -->
        <link rel="stylesheet" href="../css/admin-delete.css"/>         <!-- Individual CSS -->
    </head>
    <body class="d-flex flex-column h-100">

        <header>

            <?php
                        
            session_start();

            // Inclusion of connect-db file to be connected to the database and definition of the character encoding format and local time zone
            include('../link-db-client/connect-db.php');
            mysqli_set_charset($link_db, "utf8");
            setlocale(LC_TIME, 'fr_FR.utf8','fra');

            ?>

            <nav class="navbar navbar-expand-lg navbar-light">
                <a class="navbar-brand" href="../index.php">Spectapp</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" href='admin-add.html'>Ajout</a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link active" href='admin-delete.php'>Suppression</a>
                        </li>
                    </ul>
                    <span class="navbar-text">
                        <a class='text-white' href='admin-portal.html?deconnection=true'>Déconnexion</a>
                    </span>
                </div>
            </nav>

        </header>

        <main role="main" class="container mt-4 mb-5">
            <div class="text-center">
                <h4 class="mb-3">Supprimer un spectacle</h4>
            </div>

            <div>
                <p class='mb-1 text-center'>Selectionner un spectacle</p>
            
                <form id='form' action='../link-db-client/admin-delete-show-sender.php' method='post'>
                    <div class="input-group input-group-lg">
                    

                        <select class="custom-select" name="show_id" id="showSelect">

                            <?php

                            // Inclusion of connect-db file to be connected to the database and definition of the character encoding format
                            include('../link-db-client/connect-db.php');
                            mysqli_set_charset($link_db, "utf8");
                            setlocale(LC_TIME, 'fr_FR.utf8','fra');

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
                                        $show_artist=$show_row_assoc["show_artist"];
                                        $show_id=$show_row_assoc["show_id"];

                                        echo "<option value='".$show_id."'>".$show_name." - ".$show_artist."</option>";
                                    }
                                }
                                else    header('Location:error/error.html');
                            }
                            else    header('Location:error/error.html');

                            ?>

                        </select>
                    </div>

                    <div id="tempShowContent">
                        <div class='text-center mt-4'>
                            <div>
                                <button id="btnSubmit" class="btn btn-warning" type='submit'>Valider</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </main>

        <footer class="footer mt-auto py-2">
            <div class="footer-copyright text-center text-white">
                <a class="text-white" href="../index.php">Spectapp</a>
            </div>
        </footer>

        <script src="../js/jquery/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>                                <!-- Link to the JQuery JavaScript library -->
        <script src="../js/bootstrap/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>     <!-- Link to the Bootstrap JavaScript library -->
        <script src="../js/toastr/toastr.min.js"></script>                                                                                                                              <!-- Link to the Toastr JavaScript library -->
        <script src="../js/admin-delete.js"></script>                                                                                                                                   <!-- Link to the individual JavaScript file -->
    </body>
</html>