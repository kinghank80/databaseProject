<?php
    require_once('conn.php');
    session_start();
    // 該學生所選之課程人數-1
    $sql = sprintf("UPDATE courses SET Members = Members - 1 WHERE Cid IN (SELECT Cid FROM timetable WHERE Sid = '%s')", $_SESSION['student']['Sid']);
    $conn->query($sql);

    // 刪除該學生之所有課表資料
    $sql = sprintf("DELETE FROM timetable WHERE Sid =  '%s'", $_SESSION['student']['Sid']);
    $conn->query($sql);

    // 該學生之學分歸零
    $sql = sprintf("UPDATE students SET Credit = 0 WHERE Sid = '%s'", $_SESSION['student']['Sid']);
    $conn->query($sql);
    $_SESSION['student']['Credit'] = 0;
    header('location:home.php');
?>