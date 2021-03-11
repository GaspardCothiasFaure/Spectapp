<?php

    // Host and port of your local DBMS
    $host_port="localhost:3306";

    // User name of your local DBMS
    $user="root";

    // Password of your local DBMS
    $password="";

    //Database name :
    $db_name="spectapp-db";


    // Do not touch
    $link_db=mysqli_connect($host_port,$user,$password,$db_name);

?>
