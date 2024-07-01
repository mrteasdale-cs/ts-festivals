<?php

    define('DB_NAME', '');
    define('DB_USER', '');
    define('DB_PASSWORD', '');
    define('DB_HOST', 'localhost');


    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error){
        die("Could Not Connect to MySQL! " . $conn->connect_error);
    }



