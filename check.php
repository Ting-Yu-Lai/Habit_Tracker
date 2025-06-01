<?php
// seesion啟動
session_start();
// 匯入資料庫連線
require_once('db.php');
?>

<?php
$account = $_POST['account'];
$password = $_POST['password'];
$hash = password_hash($password, PASSWORD_DEFAULT);

// $conn->prepare 這是預備語法，防止injection(SQL攻擊)「我還不知道這是啥，可能就是駭客攻擊的手段?」
// ? 是佔位符號，用來填入使用者輸入的帳戶
$stmt = $conn->prepare("SELECT * FROM habit_tracker_users WHERE account = ?");

// bind_param()：把使用者輸入的 $acc 值綁定到前面 SQL 的 ?。
// "s" 表示是 string 類型（還有 "i" 是整數、"d" 是浮點數）。
$stmt->bind_param("s", $account);

// execute()：執行這個 SQL 語句（也就是去資料庫找這個帳號）。
$stmt->execute();

// get_result()：取得執行結果，也就是找出來的使用者資料（如果有的話）。
$result = $stmt->get_result();
var_dump($account);

// num_rows：這是查詢結果有幾筆資料。只有剛好一筆，才表示這個帳號存在。
if ($result->num_rows === 1) {
    // fetch_assoc()：把這筆資料抓出來變成「關聯陣列」的形式，像是：
    // $row['id']  
    // $row['acc']  
    // $row['pw']  
    $row = $result->fetch_assoc();
    if ($_POST['password'] == $row['password']) {
        $_SESSION['login'] = 1;
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['account'] = $row['account'];
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error'] = "密碼錯誤";
        header("Location: login_page.php");
        exit();
    }
} else {
    $_SESSION['error'] = "帳號不存在";
    header("Location: login_page.php");
    exit();
}


?>