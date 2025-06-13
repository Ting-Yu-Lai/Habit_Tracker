<?php
require_once('db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "未登入";
    exit;
}

$user_id = $_SESSION['user_id'];
$date = $_POST['date'] ?? '';
$checked = isset($_POST['checked']) ? intval($_POST['checked']) : 0;
$goal_id = $_POST['goal_id'] ?? 0;

if (empty($date) || $goal_id == 0) {
    echo "參數錯誤";
    exit;
}

// 檢查是否有紀錄
$sqlCheck = "SELECT id FROM habit_tracker_status WHERE user_id = ? AND goal_id = ? AND check_date = ?";
$stmt = $conn->prepare($sqlCheck);
$stmt->bind_param("iis", $user_id, $goal_id, $date);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // 更新
    $row = $result->fetch_assoc();
    $id = $row['id'];

    $sqlUpdate = "UPDATE habit_tracker_status SET is_checked = ?, updated_at = NOW() WHERE id = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("ii", $checked, $id);
    if ($stmtUpdate->execute()) {
        echo "更新成功";
    } else {
        echo "更新失敗";
    }
    $stmtUpdate->close();
} else {
    // 新增
    $sqlInsert = "INSERT INTO habit_tracker_status (user_id, goal_id, check_date, is_checked, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("iisi", $user_id, $goal_id, $date, $checked);
    if ($stmtInsert->execute()) {
        echo "新增成功";
    } else {
        echo "新增失敗";
    }
    $stmtInsert->close();
}

$stmt->close();
$conn->close();
?>
