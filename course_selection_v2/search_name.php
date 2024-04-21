<?php
    require_once('conn.php');
    if(isset($_POST['search_name']) && $_POST['search_name'] != ""){
        $search_name = $_POST['search_name'];
        $sql = "SELECT * FROM Courses WHERE Name LIKE '%$search_name%'";
        $result = mysqli_query($conn, $sql);
        $t = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        echo "搜尋：" . $_POST['search_name'] . "<br>";
        if(mysqli_num_rows(($result)) > 0){
            while($row = mysqli_fetch_assoc($result)){
                echo $row['Cid'] . "$t" . $row['Name'] . "$t" . $row['Department'] . "$t" . "學分:" . $row['Credit'] . "$t" . "人數:" . $row['Members'] . "/" . $row['Capacity'] . "<br>";
            }
        }
    }else{
        alert("請輸入課程名稱");
    }

    function alert($message){
        echo "<script>alert('$message');
        window.location.href='index.php';
        </script>";
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>search_name</title>
</head>
<body>
    <form action="home.php">
        <input type="submit" value="返回">
    </form>
</body>
</html>