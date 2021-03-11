<!DOCTYPE html>
<html lang="fr" class="h-100">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Spectapp</title>
        <link rel="icon" href="icon/icon.ico">
        <link rel="stylesheet" href="css/bootstrap/bootstrap.css"/>                 <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css">    <!-- Font-awesome CSS -->
        <link rel="stylesheet" href="css/spectapp-style.css"/>                      <!-- Global app CSS -->
        <link rel="stylesheet" href="css/show-critics.css"/>                        <!-- Individual CSS -->
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

            /*  Display the note on colored stars by note (out of 5) 
             *  @param $nb : note (out of 5)
             *  @return HTML code of the stars 
             */            
            function writeRate($nb) {
                $res="";
                for ($i=0; $i < 5; $i++) {
                    if ($nb>0)  $res=$res."<span class='fa fa-star or'></span>";
                    else    $res=$res."<span class='fa fa-star'></span>";
                    $nb--;
                }
                return $res;
            }

            /*  Display the show note on colored stars by id of the show
             *  @param $show_id : id of the show
             *  @param $link_db : link to database
             *  @return HTML code of the stars
             */
            function writeRatebyId($show_id,$link_db) {
                    
                if ($result_query = mysqli_query($link_db, "SELECT ROUND(AVG(`comment_rate`)) FROM `show-critic` WHERE `show_id`=".$show_id)) {
                    $response      = mysqli_fetch_array($result_query);
                    $count = $response['ROUND(AVG(`comment_rate`))'];
                    if ($count==0)  return "";
                    return writeRate($count);
                }
                else    header('Location:error/error.html');
            }

            // Verify connection to database
            if($link_db){

                // Verify receiving of performance id
                if(isset($_GET['show_id'])) {
                
                    $show_id=$_GET['show_id'];

                    // Retrieving information on the show selected at index.php in the database
                    // Verify the query went well
                    if ($result_query = mysqli_query($link_db, "SELECT * FROM `show` WHERE `show_id`=".$show_id)) {
                        $shows_tab_assoc = mysqli_fetch_assoc($result_query);

                        $show_poster_file_path="posters/".$shows_tab_assoc["show_poster_file"];
                        $show_name=$shows_tab_assoc["show_name"];
                        $show_artist=$shows_tab_assoc["show_artist"];
                        $show_description=$shows_tab_assoc["show_description"];

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
                                <p class='card-text mb-2'> <?php echo $show_artist; ?></p>
                                <?php echo writeRatebyId($show_id,$link_db); ?>
                                <p id="show-decription" class='card-text mt-2'> <?php echo $show_description; ?></p>
                                <a class='badge badge-primary' href="booking-date.php?show_id=<?php echo $show_id; ?>">Réserver</a>
                                <a class='badge badge-info' href="index.php">Retour à l'acceuil</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div id='comments'>
                <h4 class="mt-3 mb-3">Commentaires :</h4>

                <ul id='comments-list' class="list-unstyled mb-1">

                    <?php

                    // Fetch all critics of the show
                    // Verify the query went well
                    if ($result_query = mysqli_query($link_db, "SELECT * FROM `show-critic` WHERE `show_id`=".$show_id)) {
                        
                        // According to number of critics
                        if (mysqli_num_rows($result_query)>0) {

                            // Fetch row(s) of the database
                            while ($result_row = mysqli_fetch_assoc($result_query)) {
                                $show_critic_tab_assoc[]=$result_row;
                            }
                            
                            // Foreach critic => Display it (email of critic, date, comment, note)
                            foreach ($show_critic_tab_assoc as $show_critic) {
                                echo    "
                                <li class='comment-container media mb-2 rounded p-1 bg-light'>

                                    <div class='topright'>".writeRate($show_critic['comment_rate'])."</div>
                                    <div class='media-body'>
                                    <p class='mt-0 mb-1 font-weight-bold'>".$show_critic['comment_email']."<span id='comment-date' class='font-weight-light'>Le ".date("d/m/Y à H:i", strtotime($show_critic['comment_date']))."</span></p>
                                    
                                    <p>".$show_critic['comment']."<p>
                                    </div>
                                </li>
                                ";
                            }
                        }
                        else    echo    "<p>Pas de commentaires.</p>";
                    }
                    else    header('Location:error/error.html');
                    
                    ?>

                </ul>

                <div class="form-group rounded bg-light p-1 mb-5">
                    <form action="link-db-client/send-comment.php" method='POST'>

                        <label id="commentInputLabel" class="font-weight-bold" for="commentInput">Ajouter votre commentaire</label>
                        
                        <label data-toggle="tooltip" data-placement="right" title="Soumettre"> 
                            <input type="submit"  />
                            <svg  width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-chevron-double-right" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M3.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L9.293 8 3.646 2.354a.5.5 0 0 1 0-.708z"/>
                                <path fill-rule="evenodd" d="M7.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L13.293 8 7.646 2.354a.5.5 0 0 1 0-.708z"/>
                            </svg>
                        </label>   
                        
                        <textarea name='comment' class="form-control mb-1" placeholder="Votre commentaire" id="commentInput" rows="3" cols="50" required></textarea>
                        
                        <div class="star-widget">Choisissez votre note :
                            <input type="radio" value="1" name="comment_rate" id="rate-1">
                            <label for="rate-1" class="or fa fa-star"></label>
                            <input type="radio" value="2" name="comment_rate" id="rate-2">
                            <label  for="rate-2" class="or fa fa-star"></label>
                            <input type="radio" value="3" name="comment_rate" id="rate-3" checked>
                            <label for="rate-3" class="or fa fa-star"></label>
                            <input type="radio" value="4" name="comment_rate" id="rate-4">
                            <label  for="rate-4" class="or fa fa-star"></label>
                            <input type="radio" value="5" name="comment_rate" id="rate-5">
                            <label for="rate-5" class="or fa fa-star"></label>
                        </div>
                        
                        <input type="email" name='comment_email' class="form-control" placeholder="Votre email" id="text" required></input>
                        
                        <input type="hidden" name='show_id' value="<?php echo $show_id; ?>" />
                    
                    </form>  
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
        <script src="js/show-critics.js"></script>                                                                                                                                  <!-- Link to the individual JavaScript file -->
    </body>
</html>