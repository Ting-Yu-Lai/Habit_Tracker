<?php 
session_start();
require_once('db.php');

if(!isset($_SESSION['user_id'])) {
    header("Location: login_page.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$goal = $_POST['goal'] ?? '';

$stmt = $conn->prepare("SELECT id FROM goals WHERE user_id = ?");
$stmt->execute([$user_id]);

if ($stmt->num_rows > 0) {
    $stmt->close();
    $update = $conn->prepare("UPDATE goals SET goal_text = ? WHERE user_id = ?");
    $update->bind_param("si", $goal, $user_id);
    $update->execute();
    $update->close();
} else {
    $stmt->close();
    $insert = $conn->prepare("INSERT INTO goals (user_id, goal_text) VALUES (?, ?)");
    $insert->bind_param("is", $user_id, $goal);
    $insert->execute();
    $insert->close();
}

header("Location: index.php");
exit();