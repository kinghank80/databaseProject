<?php
session_start();
require_once('conn.php');


if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) 
{
    header("location: index.php");
    exit;
}

if(isset($_POST["course"])) 
{
    $course_id = $_POST["course"];
    $student_id = $_SESSION["student"]["Sid"];
    

    // 從數據庫獲取學生當前學分
    $current_credit = $_SESSION["student"]["Credit"];

    // 獲取課程訊息
    $sql = "SELECT * FROM Courses WHERE Cid = '".$course_id."'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) == 1) 
    {
        $course = mysqli_fetch_assoc($result);
        $credit_to_drop = $course["Credit"];

        // 確認課程是否為已選課程
        $sql_check = "SELECT * FROM timetable WHERE Sid = ? AND Cid = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("ss", $student_id, $course_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if(mysqli_num_rows($result_check) == 0) {
            alert("您正在嘗試退選一門您沒有選的課程，請確認。");
        }

        // 檢查退選後學分是否低於9
        if($current_credit - $credit_to_drop < 9) 
        {
            alert("退選後學分不可低於最低學分限制(9 學分)");
        }
        else
        {
            $sql = "DELETE FROM timetable WHERE Sid = ? AND Cid = ?";
            $stmt1 = $conn->prepare($sql);
            $stmt1->bind_param("ss", $student_id, $course_id);
            $stmt1->execute();

            if($course["Members"] > 0)
            {
                $sql = "UPDATE courses SET Members = Members - 1 WHERE Cid = ?";
                $stmt2 = $conn->prepare($sql);
                $stmt2->bind_param("s", $course_id);
                $stmt2->execute();
            }

            $_SESSION["student"]["Credit"] -= $credit_to_drop;  // 成功後更新學生訊息
            if($_SESSION['student']['Credit'] < 0){
                $_SESSION['student']['Credit'] = 0;
            }else{
                $sql = "UPDATE students SET Credit = Credit - $credit_to_drop WHERE Sid = ?";
                $stmt3 = $conn->prepare($sql);
                $stmt3->bind_param("s", $student_id);
                $stmt3->execute();
            }
            


            // 退選必修課發出警告
            if ($course["Is_required"] == 1) {
                alert("您正在退選一門必修課程，請確認。");
            }
            

            // 退選成功後退回home頁面
            header("location: home.php");
        }
    }
    else
    {
        alert("查無此課程");
    }
    mysqli_free_result($result);
}
else
{
    alert("Something wrong");
}

function alert($message){
    echo "<script>alert('$message');
         window.location.href='home.php';
        </script>";
    exit();
}
?>