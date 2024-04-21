<?php
    $server_name = 'localhost';
    $user_name = 'king';
    $password = 'king1234';
    $db_name = 'course_selection';

    $conn = new mysqli($server_name, $user_name, $password, $db_name);

    if(!empty($conn->connect_error)){
        die('資料庫連線錯誤:' . $conn->connect_error);
    }
?>