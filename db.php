<?php
$host = 'localhost';
$user = 'root';
$pw = '';
$dbname = 'habit_tracker_db';

$conn = new mysqli($host, $user, $pw, $dbname);
if ($conn->connect_error) {
    die("連線失敗:" . $conn->connect_error);
}

function getKeepDay($table)
{
    global $conn;
    $sql = "SELECT COUNT(*) AS keep_days FROM $table WHERE is_checked = 1";
    $result = $conn->query($sql);
    if ($result && $row = $result->fetch_assoc()) {
        return $row['keep_days'];
    }
}


function getLevelName($streak) {
    if ($streak >= 365) return "Immortal";
    if ($streak >= 200) return "Legend";
    if ($streak >= 100) return "Master";
    if ($streak >= 51) return "Iron Soul";
    if ($streak >= 31) return "Steady Walker";
    if ($streak >= 21) return "Challenger";
    if ($streak >= 14) return "Committed";
    if ($streak >= 8) return "Explorer";
    if ($streak >= 4) return "Initiator";
    return "Newcomer";
}