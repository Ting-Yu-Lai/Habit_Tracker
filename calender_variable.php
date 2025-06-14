<!-- 各項變數宣告 -->
<?php
// 今年年份
$year = date("Y");
// 當前月份
$month = date("m");
// 取得今天的日期
$toDay = date("$year-m-d");
// 取得當月的第一天
$firstDay = date("$year-$month-01", strtotime($toDay)); 
// 取得當月最後一天
$lastDay = date("$year-$month-t", strtotime($firstDay)); 
$daysOfMonth = date("t", strtotime($firstDay));
// 取得當月第一天是星期幾
$firstDayWeek = date("w", strtotime($firstDay));
// 取得當月最後一天是星期幾
$lastDayWeek = date("w", strtotime($lastDay));
// 產生陣列放入每個
$weekdays = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];
// 產生陣列可以存放當月天數
for ($i = 1; $i <= $daysOfMonth; $i++) {
    $monthOfDays[] = ['days' => $i];
}

// 運用get 取得 年與月
if (isset($_GET['year']) && isset($_GET['month'])) {
    $year = $_GET['year'];
    $month = $_GET['month'];
} else {
    // 如果沒有get到年與月，就使用當前的年與月
    $year = date("Y");
    $month = date("m");
}

if ($month - 1 > 0) {
    $preMonth = $month - 1;
    $preyear = $year;
} else {
    $preMonth = 12;
    $preyear = $year - 1;
}
if ($month + 1 < 13) {
    $nextMonth = $month + 1;
    $nextYear = $year;
} else {
    $nextMonth = 1;
    $nextYear = $year + 1;
}

$eng_month = date("F", strtotime("$year-$month-01"));
?>