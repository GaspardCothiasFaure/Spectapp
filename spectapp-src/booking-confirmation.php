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
        <link rel="stylesheet" href="css/booking-confirmation.css">     <!-- Individual CSS -->
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

        <main role="main" class="container mt-5 mb-5">

            <h2 id='userInstruction' class="h2 mb-5 text-center">Payer et valider votre réservation</h2>

            <?php

            // Inclusion of connect-db file to be connected to the database, definition of the character encoding format and definition of the local time zone
            include('link-db-client/connect-db.php');
            mysqli_set_charset($link_db, "utf8");
            setlocale(LC_TIME, 'fr_FR.utf8','fra');

            // Verify connection to database
            if($link_db){

                // Verify receiving of performance id and selected seats
                if(isset($_GET['performance_id']) && isset($_GET['selected_seats'])) {
                    
                    $performance_id=$_GET['performance_id'];
                    $seats_requested=$_GET['selected_seats'];

                    // Retrieve and display information on the performance selected
                    // Verify the query went well
                    if ($result_query = mysqli_query($link_db, "SELECT * FROM `performance` WHERE `performance_id`=".$performance_id)) {
                        $performance_row_assoc = mysqli_fetch_assoc($result_query);

                        $show_id=$performance_row_assoc["show_id"];

                        // Retrieve and display information on the show selected
                        // Verify the query went well
                        if ($result_query = mysqli_query($link_db, "SELECT * FROM `show` WHERE `show_id`=".$show_id)) {
                            $show_row_assoc = mysqli_fetch_assoc($result_query);

                            $show_name=$show_row_assoc["show_name"];
                            $show_artist=$show_row_assoc["show_artist"];
                            $poster_file_path="posters/".$show_row_assoc["show_poster_file"];
                            $nb_seats_requested=count(preg_split('/,/',$seats_requested));

                            $performance_date=$performance_row_assoc["performance_date"];
                            $performance_date=strftime("%A %e %B %G à%Hh%M",strtotime($performance_date));
                            $performance_date=str_replace('Ã','à',utf8_encode(ucfirst($performance_date)));

                        }
                        else    header('Location:error/error.html');
                    }
                    else    header('Location:error/error.html');
                }
                else    header('Location:error/error.html');
            }
            else    header('Location:error/error.html');

            ?>

            <div class="row">
                <div class="col-md-4 order-md-2 mb-4">
                    <h4 class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Votre réservation</span>
                    </h4>
                    <ul class="list-group mb-3">
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                            <div>
                                <h6 id="show_name_info" class="my-0"><?php echo $show_name; ?></h5></h6>
                                <small id="show_artist_info" class="text-muted"><?php echo $show_artist; ?></small><br>
                                <small id="performance_date_info" class="text-muted"><?php echo $performance_date; ?></small><br>
                                <small class="text-muted">Place(s) n° <?php echo $seats_requested; ?></small><br>
                            </div>
                            <span class="text-muted"><span id="reservationPrice"></span>€</span>
                        </li>
                        <li id="totalPriceContainer" class="list-group-item d-flex justify-content-between">
                            <span>Total</span>
                            <strong><span id="totalPriceValue"></span>€</strong>
                        </li>
                    </ul>

                <div id="addPromoCode" class="card p-2">
                    <div class="input-group">
                        <input id='promoCode' type="text" class="form-control" placeholder="Code promo">
                        <div class="input-group-append">
                            <button id="promoBtn" type="submit" class="btn btn-secondary" disabled>Ajouter</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="colRigth" class="col-md-8 order-md-1">
                <form id="userForm" class="needs-validation">
                    <h4 class="mb-3">Vos informations</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="firstNameInput">Prénom</label>
                            <input type="text" class="form-control" id="firstNameInput" placeholder="Prénom" value="" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lastNameInput">Nom</label>
                            <input type="text" class="form-control" id="lastNameInput" placeholder="Nom" value="" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="emailInput">Adresse email</label>
                        <input type="email" class="form-control" id="emailInput" placeholder="Email" required>
                    </div>
                    
                    <hr class="mb-3 mt-4">
                    <h4 class="mb-0">Paiement</h4>
                    <small class="text-muted">Par carte banquaire uniquement</small>

                    <div class="mt-3">
                        <label class="mr-3">Type de carte :</label>
                        <label class="mr-3">
                            <input type="radio" name="cardType" value="Visa" checked required>
                            <img src="assets/visa-logo.jpg" width="40">
                        </label>
                        <label>
                            <input type="radio" name="cardType" value="Mastercard">
                            <img src="assets/mastercard-logo.jpg" width='40'>
                        </label>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ccNameInput">Nom complet sur la carte</label>
                            <input type="text" class="form-control" id="ccNameInput" placeholder="Nom" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ccNumberInput">Numéro de carte</label>
                            <input type="text" class="form-control" id="ccNumberInput" placeholder="Numéro" required minlength="16" maxlength="16">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-4">
                            <label for="cc-expiration">Date d'éxpiration</label>
                            <input type="text" class="form-control" id="cc-expiration" placeholder="mm/aa" minlength="5" maxlength="5" required>
                        </div>
                        <div class="col-md-3 mb-4">
                            <label for="cc-cvv">Cryptogramme</label>
                            <input type="text" class="form-control" id="cc-cvv" placeholder="Crypto" minlength="3" maxlength="3" required>
                        </div>
                    </div>
                    <hr class="mb-3 mt-1">
                    <button id="checkoutBtn" class="btn btn-primary btn-lg btn-block" type="submit">Confirmer et payer la réservation</button>
                </form>
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
        <script src="js/booking-confirmation.js"></script>                                                                                                                          <!-- Link to the individual JavaScript file -->
    </body>
</html>