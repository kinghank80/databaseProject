<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register</title>
</head>
<body>
    <div action="register.php" class="register">
        <form method="post">
            <label for="sid">學號</label>
            <input type="text" name="sid">
            <br>
            <label for="grade">年級</label>
            <input type="text" name="grade">
            <br>
            <label for="dept">系所</label>
            <input type="text" name="dept">
            <br>
            <input type="submit" value="送出">
        </form>
    </div>

    <form action="index.php">
        <input type="submit" value="返回">
    </form>
</body>
</html>

<?php
    require_once('conn.php');
    session_start();
    if(isset($_POST['sid']) && isset($_POST['grade']) && isset($_POST['dept'])){
        // 學號必須要8位數
        if(strlen($_POST['sid']) != 8){
            alert("學號必須是8位數");
        }elseif($_POST['grade']!="一" && $_POST['grade']!="二" && $_POST['grade']!="三" &&$_POST['grade']!="四")
            alert("年級請輸入中文");

        // 將該註冊學生INSERT至students中
        $sql = sprintf("INSERT INTO students(Sid, Grade, Department)
                        VALUES('%s', '%s', '%s')", $_POST['sid'], $_POST['grade'], $_POST['dept']);
        $conn->query($sql);

        // 判斷年級與科系
        $temp_dept = $_POST['dept'];
        if($_POST['grade'] == "一"){
            $sql = "SELECT * FROM courses WHERE Cid like '1%' AND Department = '$temp_dept'";
        }else if($_POST['grade'] == "二"){
            $sql = "SELECT * FROM courses WHERE Cid like '2%' AND Department = '$temp_dept'";
        }else if($_POST['grade'] == "三"){
            $sql = "SELECT * FROM courses WHERE Cid like '3%' AND Department = '$temp_dept'";
        }else if($_POST['grade'] == "四"){
            $sql = "SELECT * FROM courses WHERE Cid like '4%' AND Department = '$temp_dept'";
        }
        
        // 一行一行匯入課程
        $result = $conn->query($sql);
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                if($row['Is_required'] == 1){  // 如果是必修課
                    // 把課程匯入該註冊學生之課表
                    $sql = sprintf("INSERT timetable(Sid, Cid, Course_name, Week, Start, End)
                                    VALUES('%s', '%d', '%s', '%d', '%d', '%d')", $_POST['sid'], $row['Cid'], $row['Name'], $row['Week'], $row['Start'], $row['End']);
                    $conn->query($sql);

                    // 改變students中的Credit
                    $sql = sprintf("UPDATE students SET Credit = Credit + %d WHERE Sid = '%s'", $row['Credit'], $_POST['sid']);
                    $conn->query($sql);

                    // 把預選之課程人數+1
                    $sql = sprintf("UPDATE courses SET Members = Members + 1 WHERE Cid = %s", $row['Cid']);
                    $conn->query($sql);
                    if($row['Members'] == $row['Capacity']){
                        $sql = sprintf("UPDATE courses SET Capacity = Capacity + 1 WHERE Cid = %s", $row['Cid']);
                        $conn->query($sql);
                    }
                }
            }
            alert("註冊成功");
        }
    }

    function alert($message){
        echo "<script>alert('$message');
        </script>";
        exit();
    }
?>