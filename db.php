<?php
    $svr = "localhost";
    $usr = "root";
    $pw = "";
    $db = "webperpus";

    $conn = mysqli_connect($svr, $usr, $pw, $db);

    if (!$conn) {
        echo die("Connection Failed! ". mysqli_connect_error());
    };
?>