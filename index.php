<?php
session_start();
require_once('db.php');
require_once('calender_variable.php');
if (!isset($_SESSION['user_id'])) {
  header("Location: login_page.php");  // 或是你的登入頁
  exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT goal_text FROM goals WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($goal);
$stmt->fetch();
$stmt->close();
?>




<!--##################################################主程式開始################################################## -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Habit Tracker</title>
  <link rel="stylesheet" href="./style.css" />
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
    }

    h1 {
      font-size: 64px;
    }

    header {
      width: 100%;
      height: 10vh;
      padding: 10px;
      background-color: lightblue;
      display: flex;
      justify-content: space-between;
    }

    .header-right {
      display: flex;
      justify-content: center;
      align-items: center;
      font-weight: bold;
      font-size: 32px;
    }

    .header-right>div,
    span {
      padding: 10px
    }

    .username {
      font-weight: bold;
      font-size: 24px;
      color: darkblue;
    }

    .goal-bar {
      width: 100%;
      height: 10vh;
      /* background-color: lightpink; */
      padding: 10px
    }

    .rulesModal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 1000;
      justify-content: center;
      align-items: center;
    }

    #openRules {
      border: none;
      background-color: transparent;
      font-size: 32px;
      color: black;
      font-weight: bold;
    }

    input {
      width: 300px;
      height: calc(100% - 3vh);
      font-size: 28px;
      border: none;
      font-weight: bold;
      /* background-color: red; */
    }

    .goal-text {
      height: 3vh;
      font-weight: 500;
      font-style: italic;
      color: lightgray;
      padding-left: 1em;
      /* background-color: lightblue; */
    }

    .main {
      width: 100%;
      height: 75vh;
      background-color: lightgray;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .main-left {
      width: 70%;
      height: 65vh;
      margin: auto;
      /* background-color: red; */
    }

    .container-calender {
      width: 100%;
      height: 100%;
      /* background-color: blue; */
      display: flex;
      flex-direction: column;
      /* justify-content: space-between; */
      /* align-items: center; */
      z-index: 1;

    }


    .box-calender {
      width: 100%;
      height: 100%;
      background-color: transparent;
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      align-items: center;
    }

    .main-right {
      width: 25%;
      height: 65vh;
      /* background-color: lightgreen; */
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }

    .tracker-box {
      width: 70%;
      height: 30vh;
      /* background-color: blue; */
      margin-bottom: 5px;
    }

    .my-streak {
      font-size: 24px;
      font-weight: bold;
    }

    .consistency {
      margin-top: 30px;
      /* background-color: #fff; */
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .level {
      /* margin-top: 30px; */
      /* background-color: #fff; */
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .medal-box {
      width: 70%;
      height: 30vh;
      /* background-color: red; */
    }

    footer {
      width: 100%;
      height: 5vh;
      background-color: lightcyan;
      text-align: center;
    }

    a {
      /* font-weight: bold; */
      color: black;
      text-decoration: none;
    }

    a:hover {
      color: gray;
    }
  </style>
</head>

<body>
  <!-- header -->
  <header>
    <h1>Habit Tracker</h1>
    <div class="header-right">
      <div>
        <?php
        if (isset($_SESSION['login']) && $_SESSION['login'] === 1) {
          echo "<strong>{$_SESSION['username']}</strong> <a href='logout.php'>Logout</a>";
        } else {
          echo  "<a href='./login_page.php'>Login</a>";
        }
        ?>
      </div>
      <span>/</span>
      <div>
        <button type="button" id="openRules">Rules</button>
      </div>
      <span>/</span>
      <div>About</div>
    </div>
  </header>

  <!-- goal bar -->
  <div class="goal-bar">
    <form action="./goal_check.php" method="post">
      <?php if ($goal): ?>
        <input type="text" name="goal" id="" placeholder="<?= htmlspecialchars($goal) ?>">
      <?php else: ?>
        <input type="text" name="goal" id="" placeholder="You haven't set a goal yet.">
      <?php endif; ?>
      <?php if (!$goal): ?>
        <button type="submit">確認</button>
      <?php else: ?>
        <button type="submit">更新</button>
      <?php endif; ?>
    </form>
    <div class="goal-text">I'm a person who really loves <?= $goal ?> </div>
    <!-- <pre> 印出變數的內容 
    <?php var_dump($goal); ?>
    </pre> -->
  </div>

  <!-- main -->
  <div class="main">
    <?php if (isset($_SESSION['user_id'])): ?>
      <div class="main-left">
        <div class="container-calender">
          <div class="box-calender">
            <?php include('./calender.php'); ?>
          </div>
        </div>
      </div>
    <?php else: ?>
      <p>請先登入才能查看日曆</p>
    <?php endif; ?>
    <div class="main-right">
      <div class="tracker-box">
        <div class="my-streak">
          My Streak</div>
        <div class="consistency">
          Consistency: <?= getKeepDay('habit_tracker_status') ?> days
        </div>
        <div class="level">
          Level: <?= getLevelName(getKeepDay('habit_tracker_status')) ?>
        </div>
      </div>
      <div class="medal-box"></div>
    </div>
  </div>

  <!-- footer -->
  <footer>
    © 2025 Akuma-Yu.
  </footer>

  <div class="rulesModal">
    <h2>Rules</h2>
    <button type="button" id="closeRules">close</button>
  </div>
  <script>
    // bind
    openRules = document.getElementById('openRules');
    closeRules = document.getElementById('closeRules');
    // action
    openRules.addEventListener('click', function() {
      document.querySelector('.rulesModal').style.display = 'flex';
    });
    closeRules.addEventListener('click', function() {
      document.querySelector('.rulesModal').style.display = 'none';
    });
  </script>
</body>

</html>