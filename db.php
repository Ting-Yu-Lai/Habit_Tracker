<?php
$host = 'localhost';
$user = 'root';
$pw = '';
$dbname = 'habit_tracker_db';

$conn = new mysqli($host, $user, $pw, $dbname);
if ($conn->connect_error) {
    die("連線失敗:" . $conn->connect_error);
}

?>
