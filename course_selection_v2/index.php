<?php
    session_start();

    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true){
        header("location:home.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>index</title>
</head>
<body>
    <h1>學生登入</h1>
    <div class="login">
        <form action="login.php" method="post">
            <label for="sid">學號</label>
            <input type="text" placeholder="請輸入學號" name="sid">
            <input type="submit" value="登入">
        </form>
    </div>

    <div class="register" style="margin-top:10px;">
        <form action="register.php">
            <input type="submit" value="註冊">
        </form>
    </div>
</body>
</html>