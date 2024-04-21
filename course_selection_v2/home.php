<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>選課系統</title>
    <style>
        table {
            border-spacing: 0;
        }
        td {
            width: 50px;
            height: 50px;
            border: 1px solid black;
            padding: 0;
            text-align: center;
            font-size: 12px;
        }

        #left{
            width:400px;
            height:400px;
            text-align:center;
            float:left;
        }

        .timetable{
            margin:0 50px;
            width:200px
            height:400px;
            text-align:center;
            float:left;
        }

        .showCourse{
            margin: 0 50px;
            width:400px;
            text-align:center;
            float:left;
        }
    </style>
</head>

<body>
    
    <h1 style="text-align:center;">選課系統晨大戲院</h1>

    <div id="hello" style="width:200px">
        <?php
            session_start();
            echo "您好 " . $_SESSION["student"]["Sid"] . "<br>";
            echo $_SESSION["student"]["Department"] . $_SESSION["student"]["Grade"] . "年級" . "<br>";
            echo "目前學分數：" . $_SESSION["student"]["Credit"] . "<br>";
        ?>
    </div>

    <div class="logout" style="float:left; width:50px;">
        <form action="logout.php">
            <input type="submit" value="登出">
        </form>
    </div>

    <div class='deleteAll' style="width:200px">
        <form action="deleteAll.php" method="post">
            <input type="submit" onclick="return confirm('Anyway 恕不負責')" value="刪除全部選課資料" >
        </form>
    </div>

    <div id="left">
        <div class="search" style="width: 350px; margin-top: 20px;">
            <fieldset>
                <legend>搜尋</legend>
                <form action="search_name.php "method="post" style="width:auto">
                    <label>課程名稱</label>
                    <input type="text" name="search_name">
                    <input type="submit" value="搜尋">
                </form>
                <br>
                <form action="search_cid.php" method="post">
                    <label>課程代碼</label>
                    <input type="text" name="search_cid">
                    <input type="submit" value="搜尋">
                </form>
            </fieldset>
        </div>

        <div class="addWithdraw" style="width: 350px; margin-top: 20px;">
            <fieldset>
                <legend>加退選</legend>
                <form action="select.php" method="post" style="width:auto">
                    <label>加選</label>
                    <input type="text" placeholder="請輸入課程代碼" name="select">
                    <input type="submit" onclick="return confirm('加選')" value="加選">
                </form>

                <form action="drop_course.php" method="post" style="width:auto">
                    <label>退選</label>
                    <input type="text" placeholder="請輸入課程代碼" name="course">
                    <input type="submit" onclick="return confirm('退選')" value="退選">
                </form>
            </fieldset>
        </div>
    </div>

    <div class='timetable' style="margin-top: 10px; margin-bottom: 10px;">
        <table>
            <tbody>
            <?php
            require_once('conn.php');
            $sid_temp = $_SESSION['student']['Sid'];
            $timetable = mysqli_query($conn, "SELECT * FROM timetable WHERE Sid = '$sid_temp'");
            
            // 初始化一個空的表格
            $table = [];

            // 定義星期幾和課時
            $days_of_week = ['星期一', '星期二', '星期三', '星期四', '星期五', '星期六', '星期日'];
            $class_periods = range(1, 14);

            // 第一行只寫星期幾
            $table[] = array_merge([''], $days_of_week);

            // 從第二行開始寫入每節的課時以及相應的內容
            foreach ($class_periods as $period) {
                $row = ["第 $period 節"];
                // 其他單元格留空
                foreach ($days_of_week as $day) {
                    $row[] = ''; // 在這裡添加您想要的內容，如課程名稱、教室等
                }
                $table[] = $row;
            }

            // 在表格中指定位置添加課程資訊
            if(mysqli_num_rows($timetable) > 0) {
                while($row1 = mysqli_fetch_assoc($timetable)) {
                    for($i = $row1['Start']; $i <= $row1['End']; $i++) {
                        $sql = sprintf("SELECT * FROM courses WHERE Cid = %d", $row1['Cid']);
                        $res_courses = mysqli_fetch_assoc(mysqli_query($conn, $sql));
                        if($res_courses['Is_required'] == 1){
                            if($res_courses['Cid'][0] == 1 && $_SESSION['student']['Grade'] == '一'){
                                $table[$i][$row1['Week']] = "*" . $row1['Cid'] . " " . $row1['Course_name'];
                            }else if($res_courses['Cid'][0] == 2 && $_SESSION['student']['Grade'] == '二'){
                                $table[$i][$row1['Week']] = "*" . $row1['Cid'] . " " . $row1['Course_name'];
                            }else if($res_courses['Cid'][0] == 3 && $_SESSION['student']['Grade'] == '三'){
                                $table[$i][$row1['Week']] = "*" . $row1['Cid'] . " " . $row1['Course_name'];
                            }else if($res_courses['Cid'][0] == 4 && $_SESSION['student']['Grade'] == '四'){
                                $table[$i][$row1['Week']] = "*" . $row1['Cid'] . " " . $row1['Course_name'];
                            }else{
                                $table[$i][$row1['Week']] = $row1['Cid'] . " " . $row1['Course_name'];
                            }
                        }else if($res_courses['Is_required'] == 0){
                            $table[$i][$row1['Week']] = $row1['Cid'] . " " . $row1['Course_name'];
                        }
                    }
                }
            }

            // 生成表格的 HTML
            foreach ($table as $row) {
                echo '<tr>';
                foreach ($row as $cell) {
                    echo "<td>$cell</td>";
                }
                echo '</tr>';
            }
            ?>
            </tbody>
        </table>
    </div>

    <div class='showCourse'>
        <?php
            require_once('conn.php');
            // 確保使用者已登入
            if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
                header("location: index.php");
                exit();
            }
            
            // 獲取該學生的系別
            $department = $_SESSION['student']['Department'];
            
            // 獲取該學生已選修的課程的課程ID
            $student_id = $_SESSION['student']['Sid'];
            $sql_selected_courses = "SELECT Cid FROM timetable WHERE Sid = ?";
            $stmt_selected_courses = $conn->prepare($sql_selected_courses);
            $stmt_selected_courses->bind_param("s", $student_id);
            $stmt_selected_courses->execute();
            $result_selected_courses = $stmt_selected_courses->get_result();
            
            // 將已選修課程ID放入數組中
            $selected_course_ids = [];
            while ($row = $result_selected_courses->fetch_assoc()) {
                $selected_course_ids[] = $row['Cid'];
            }
            
            // 構造 SQL 查詢，查找該學生能選擇的所有課程
            if (!empty($selected_course_ids) && $selected_course_ids != []) {
                $sql_available_courses = "SELECT * FROM Courses WHERE Department = ? AND Cid NOT IN (" . implode(",", $selected_course_ids) . ")";
            } else {
                $sql_available_courses = "SELECT * FROM Courses WHERE Department = ? ";
            }
            
            $stmt_available_courses = $conn->prepare($sql_available_courses);
            $stmt_available_courses->bind_param("s", $department);
            $stmt_available_courses->execute();
            $result_available_courses = $stmt_available_courses->get_result();
            // 顯示可選課程列表
            if ($result_available_courses->num_rows > 0) {
                echo "<h2>可選課列表</h2>";
                echo "<table style='margin-left:auto; margin-right:auto'>";
                echo "<tr><th style='width:70px'>課程代碼</th><th style='width:70px'>課程名稱</th><th>學分</th><th>星期</th><th>節數</th><th>人數</th></tr>";
                $timetable = mysqli_query($conn, "SELECT * FROM timetable WHERE Sid = '$student_id'");
                $row1[] = [];
                $i=0;
                if(mysqli_num_rows($timetable) > 0)
                {
                    while($XD = mysqli_fetch_assoc($timetable))
                    {
                        $row1[$i] = $XD;
                        $i++;
                    }
                }
                while ($row = $result_available_courses->fetch_assoc())
                {
                    $flag=0;
                    for($z = 0 ; $z < $i ; $z++)
                    {
                        if($row1[$z]['Week'] == $row['Week'] && ($row1[$z]['Start'] >= $row['Start'] && $row1[$z]['Start'] <= $row['End'] || ($row1[$z]['End'] <= $row['End'] && $row['Start'] <= $row1[$z]['End'] )))
                            $flag=1;
                        if($row1[$z]['Course_name'] == $row['Name']){
                            $flag=1;
                        }
                    }
                    if($flag==0 && $row['Members'] < $row['Capacity'] && $row['Credit']+$_SESSION['student']['Credit'] <= 30)
                    {
                        echo "<tr>";
                        echo "<td>" . $row['Cid'] . "</td>";
                        echo "<td>" . $row['Name'] . "</td>";
                        echo "<td>" . $row['Credit'] . "</td>";
                        echo "<td>" . $row['Week'] . "</td>";
                        echo "<td>" . $row['Start'] . "-" . $row['End'] . "</td>";
                        echo "<td>" . $row['Members'] . "/" . $row['Capacity'] . "</td>";
                        echo "</tr>";
                    }
                }
                echo "</table>";
            } else {
                echo "目前沒有可選的課程。";
            }
            
            // 釋放結果集和關閉連接
            $stmt_selected_courses->close();
            $stmt_available_courses->close();
            $result_selected_courses->close();
            $result_available_courses->close();
            $conn->close();
        ?>
    </div>
</body>
</html>