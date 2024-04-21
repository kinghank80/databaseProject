<?php
    require_once('conn.php');

    if(isset($_POST["sid"])){
        $sid = $_POST["sid"];
        $sql = "SELECT * FROM Students WHERE Sid = '".$sid."'";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) == 1){
            session_start();
            $_SESSION["loggedin"] = true;
            $_SESSION["student"] = mysqli_fetch_array($result);
            header("location:home.php");
        }else{
            alert("查無此學號");
        }
    }else{
        alert("Something wrong");
    }

    mysqli_free_result($result);

    function alert($message){
        echo "<script>alert('$message');
        window.location.href='index.php';
        </script>";
        exit();
    }
?>