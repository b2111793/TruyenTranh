<?php
    $host = "localhost";
    $username = "root";
    $password = "";
    $dbname = "webbanhangphp";

    $conn = new mysqli($host, $username, $password, $dbname);
    if($conn->connect_error){
        die("Kết nối thất bại: " . $conn->connect_error);
    }
    $conn->set_charset("utf8");
?>