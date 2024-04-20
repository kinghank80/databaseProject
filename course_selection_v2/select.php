<?php
    require_once('conn.php');
    session_start();
    if(isset($_POST['select']) && $_POST['select'] != ""){
        // 所選之課程資訊
        $sql = sprintf("SELECT * FROM Courses WHERE Cid = %s", $_POST['select']);
        $res_course = mysqli_fetch_assoc(mysqli_query($conn, $sql));

        // 該學生之時間表資訊
        $sql = sprintf("SELECT * FROM timetable WHERE Sid = '%s'", $_SESSION['student']['Sid']);
        $res_timetable = mysqli_query($conn, $sql);      

        // 同學只能加選本系的課程
        if($_SESSION['student']['Department'] != $res_course['Department']){
            alert("不可加選其他系的課程");
        }

        // 人數已滿的課程不可加選
        if($res_course['Members'] >= $res_course['Capacity']){
            alert('課程人數已滿');
        }

        // 不可加選衝堂的課程
        while ($row = $res_timetable->fetch_assoc()){
            // 不可加選衝堂的課程
            if ($row['Week'] == $res_course['Week']){
                if (($res_course['Start'] <= $row['Start'] && $row['Start'] <= $res_course['End']) ||
                    ($res_course['Start'] <= $row['End'] && $row['End'] <= $res_course['End'])){
                    alert('不可加選衝堂課程');
                }
            }

            //不可加選與已選課程同名的課程
            if($row['Course_name'] == $res_course['Name']){
                alert("不可加選同名課程");
            }
        }

        $sql = sprintf("SELECT Week, Start, End FROM timetable WHERE Sid = '%s'", $_SESSION['student']['Sid']);

        // 加選後學分不可超過最高學分限制 (30 學分)
        if($_SESSION['student']['Credit'] + $res_course['Credit'] > 30){
            alert('學分超出');
        }

        // 把課程插入該學生的課表
        $sql = sprintf("INSERT INTO timetable(SId, Cid, Course_name, Week, Start, End) VALUES('%s', '%d', '%s', '%d', '%d', '%d')",
                        $_SESSION['student']['Sid'], $res_course['Cid'], $res_course['Name'], $res_course['Week'], $res_course['Start'], $res_course['End']);
        $conn->query($sql);

        // 把學分數加入該學生之學分數
        $sql = sprintf("UPDATE students SET Credit = Credit + %d WHERE Sid = '%s'", $res_course['Credit'], $_SESSION['student']['Sid']);
        $conn->query($sql);
        $_SESSION['student']['Credit'] += $res_course['Credit'];

        // 把該課程人數+1
        $sql = sprintf("UPDATE courses SET Members = Members + 1 WHERE Cid = %s", $_POST['select']);
        $conn->query($sql);
        alert("加選成功");
    }else{
        alert("請輸入課程代碼");
    }

    function alert($message){
        echo "<script>alert('$message');
        window.location.href='index.php';
        </script>";
        exit();
    }
?>