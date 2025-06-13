<!-- 資料庫連線 -->
<?php
require_once('calender_variable.php');
require_once('db.php');

if (!isset($_SESSION['user_id'])) {
    return;  // 沒登入就不執行下面
}

$user_id = $_SESSION['user_id'];

// 先撈出使用者的 goal_id
$sqlGoal = "SELECT id FROM goals WHERE user_id = ?";
$stmtGoal = $conn->prepare($sqlGoal);
$stmtGoal->bind_param("i", $user_id);
$stmtGoal->execute();
$resultGoal = $stmtGoal->get_result();
$goal_id = null;
if ($rowGoal = $resultGoal->fetch_assoc()) {
    $goal_id = $rowGoal['id'];
}
$stmtGoal->close();

// 撈當月該目標已勾選的日期
$startDate = "$year-$month-01";
$endDate = "$year-$month-31";

$sql = "SELECT check_date FROM habit_tracker_status 
        WHERE user_id = ? AND goal_id = ? AND is_checked = 1 AND check_date BETWEEN ? AND ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiss", $user_id, $goal_id, $startDate, $endDate);
$stmt->execute();
$result = $stmt->get_result();

$checkedDates = [];
while ($row = $result->fetch_assoc()) {
    $checkedDates[$row['check_date']] = true;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .bar-box {
            width: 100%;
            height: 100px;
            background-color: transparent;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 3px;
        }

        #today {
            background-image: url(./image/redball.png);
            background-repeat: no-repeat;
            background-size: contain;
            background-position: center;
            color: black;
            font-weight: bold;
            font-size: 30px;

        }

        .box-week {
            width: calc(100% / 7);
            height: 66.86px;
            background-color: transparent;
            /* border-bottom: 1px solid #ccc; */
            margin-left: -1px;
            margin-bottom: -1px;
            text-align: center;
            color: rgb(241, 248, 255);
            border-radius: 50%;
            padding-top: 20px;
        }

        /* 星期一到星期日的外框 */

        .box-day {
            width: calc(100% / 7);
            height: 66.86px;
            background-color: transparent;

            margin-left: -1px;
            margin-bottom: -1px;

            display: flex;
            align-items: center;
            justify-content: center;

            color: #f1f8ff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            /* 字型 */
            font-size: 18px;
            /* 字體大小 */
            font-weight: 500;
            /* 字體粗細 */

            border-radius: 50%;
            /* 圓形外觀 */
            transform: scale(0.85);
            /* 稍微小一點但不壓縮太過 */
            transition: all 0.2s ease-in-out;
            /* 增加動態感 */
        }

        .day-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .date-number {
            font-size: 18px;
            font-weight: bold;
            color: #f1f8ff;
            margin-bottom: 4px;
        }

        .check-day {
            width: 18px;
            height: 18px;
            accent-color: #ff6b6b;
            /* 讓 checkbox 更美觀 */
            cursor: pointer;
        }


        .box-yearandmonth {
            width: 100%;
            height: 50px;
            /* background-color: #f0f0f0; */
            display: flex;
            justify-content: space-between;
            /* align-items: center; */
            /* border-bottom: 1px solid #ccc; */
        }

        .box-year {
            text-align: center;
            /* padding-top: 15px; */
            font-size: 35px;
            width: 50%;
            height: 100%;
            background-color: transparent;
            color: rgb(235, 34, 93);
            text-shadow: 3px 3px 12px rgb(200, 50, 50);
            position: relative;
        }

        .month-left {
            width: 40px;
            height: 40px;
            /* background-color: pink; */
            position: absolute;
            top: 0;
            left: 40px;
            background-image: url(./image/left_icon.png);
            background-repeat: no-repeat;
            background-size: cover;
        }

        .box-month {
            text-align: center;
            font-size: 35px;
            width: 50%;
            height: 100%;
            background-color: transparent;
            color: rgb(235, 34, 93);
            position: relative;
        }

        .month-right {
            width: 40px;
            height: 40px;
            /* background-color: pink; */
            position: absolute;
            top: 0;
            right: 40px;
            background-image: url(./image/right_icon.png);
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
    </style>
</head>

<body>
    <!-- <h2><?php echo $year . "年" . $month . "月"; ?></h2> -->

    <div class="box-yearandmonth">
        <div class="box-year">
            <?php echo "$year"; ?>
            <a id="preMonthBtn" class="month-left" href="index.php?year=<?php echo $preYear; ?>&month=<?php echo $preMonth; ?>"></a>
        </div>
        <div class="box-month">
            <?php echo "$eng_month"; ?>
            <a id="nextMonthBtn" class="month-right" href="index.php?year=<?php echo $nextYear; ?>&month=<?php echo $nextMonth; ?>"></a>
        </div>
    </div>

    <!-- 使用div class=box 顯示日歷禮拜一到七 -->
    <?php

    foreach ($weekdays as $weekday) {
        echo "<div class = 'box-week'>";
        echo $weekday;
        echo "</div>";
    }

    ?>

    <!-- 顯示補足第一天可能是在禮拜四或五的空白位置 -->
    <?php
    $boxposition = 0;
    for ($i = 0; $i < $firstDayWeek; $i++) {
        echo "<div class='box-day'></div>";
    }
    ?>

    <!-- 運用 <div class="box"> 產生存放日期空格 -->
    <?php
    foreach ($monthOfDays as $days) {
        $day = str_pad($days['days'], 2, '0', STR_PAD_LEFT);  // 補0
        $currentDate = "$year-$month-$day";
        $isChecked = isset($checkedDates[$currentDate]) ? 'checked' : '';

        // 判斷今天加 class
        $class = ($currentDate == $toDay) ? "box-day today" : "box-day";

        echo "<div class='$class'>";
        echo "<div class='day-content'>";
        echo "<div class='date-number'>{$days['days']}</div>";
        echo "<input type='checkbox' class='check-day' data-date='{$currentDate}' data-goal-id='{$goal_id}' {$isChecked}>";
        echo "</div>";

        echo "</div>";
        $boxposition++;
    }


    for ($i = $lastDayWeek; $i < 6; $i++) {
        echo "<div class='box-day'></div>";
    }

    ?>

    <script>
        document.querySelectorAll('.check-day').forEach(box => {
            box.addEventListener('change', function() {
                const date = this.dataset.date;
                const goalId = this.dataset.goalId;
                const checked = this.checked ? 1 : 0;

                fetch('update_status.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `date=${date}&goal_id=${goalId}&checked=${checked}`
                    })
                    .then(res => res.text())
                    .then(msg => console.log('狀態更新:', msg))
                    .catch(err => console.error('錯誤:', err));
            });
        });
    </script>
</body>

</html>