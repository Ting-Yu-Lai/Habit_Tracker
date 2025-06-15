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
      /* background-color: lightblue; */
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

    .goal-bar button {
      height: 40px;
      padding: 0 20px;
      font-size: 16px;
      font-weight: bold;
      background-color: #000;
      color: #fff;
      /* border: none; */
      border-radius: 6%;
      cursor: pointer;
      transition: background-color 0.3s ease, color 0.3s ease;
    }

    .goal-bar button:hover {
      background-color: #fff;
      color: #000;
      /* border: 2px solid #000; */
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
    
    #openRules:hover {
      color: gray;
    }
    
    #openAbout {
      border: none;
      background-color: transparent;
      font-size: 32px;
      color: black;
      font-weight: bold;
    }
    
    #openAbout:hover {
      color: gray;
    }

    .modal-content {
      background-color: #fff;
      padding: 30px;
      border-radius: 12px;
      width: 90%;
      max-width: 500px;
      text-align: left;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
      font-family: 'Segoe UI', sans-serif;
      color: #333;
    }

    .rulesModalContent h2 {
      text-align: center;
      margin-bottom: 20px;
      font-size: 28px;
      color: #2c3e50;
    }

    .rules-list {
      list-style: decimal inside;
      padding-left: 0;
      font-size: 18px;
      line-height: 1.6;
    }

    .rules-list li {
      margin-bottom: 15px;
    }

    .aboutModal {
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

    #closeRules {
      display: block;
      margin: 20px auto 0;
      padding: 10px 20px;
      background-color: #000;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 18px;
    }

    #closeRules:hover {
      color: white;
      background-color: rgb(42, 51, 59);
    }
    
    #closeAbout {
      display: block;
      margin: 20px auto 0;
      padding: 10px 20px;
      background-color: #000;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 18px;
    }

    #closeAbout:hover {
      color: white;
      background-color: rgb(42, 51, 59);
    }

    .about-container {
      padding: 40px;
      max-width: 800px;
      margin: auto;
      color: black;
      background-color: white;
      font-family: sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      flex-wrap: wrap;
    }

    .about-container h2 {
      font-size: 36px;
      margin-bottom: 20px;
      color:rgb(0, 0, 0);
    }

    .about-container h3 {
      margin-top: 30px;
      color: rgb(0, 0, 0);
    }

    .about-container p {
      line-height: 1.6;
      font-size: 18px;
      color: rgb(0, 0, 0);
    }

    .about-container ul {
      margin-top: 10px;
      padding-left: 20px;
    }

    .about-container li {
      margin-bottom: 10px;
      font-size: 16px;
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
      /* background-color: lightgray; */
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
      font-size: 32px;
      font-weight: bold;
      display: flex;
      justify-content: center;
      align-items: center;
      /* background-color: lightcoral; */
    }

    .consistency {
      margin-top: 20px;
      font-size: 24px;
      /* background-color: lightblue; */
    }

    .level {
      margin-top: 10px;
      font-size: 24px;
      /* background-color: lightblue; */
    }

    .medal-box {
      width: 70%;
      height: 30vh;
      /* background-color: red; */
    }

    footer {
      width: 100%;
      height: 5vh;
      /* background-color: lightcyan; */
      text-align: center;
      display: flex;
      justify-content: center;
      align-items: center;
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
      <button type="button" id="openAbout">About</button>
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
        <button type="submit">Set Goal</button>
      <?php else: ?>
        <button type="submit">Edit Goal</button>
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
          My Streak
        </div>
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
    <div class="modal-content">
      <h2>Habit Tracker Rules</h2>
      <ol class="rules-list">
        <li><strong>Mark it every day</strong><br>
          — Don’t aim for perfection. Just keep going.</li>
        <li><strong>Never miss twice</strong><br> — Missing once is okay, but don’t let it become a new habit.</li>
        <li><strong>Make progress visible</strong><br> — Seeing your streak helps you stay motivated.</li>
      </ol>
      <button type="button" id="closeRules">close</button>
    </div>
  </div>

  <div class="aboutModal">
    <div class="about-container">
      <h2>About Habit Tracker</h2>
      <p>This is a minimalist habit tracking tool inspired by <em>Atomic Habits</em>. Our goal is to help you stay consistent with your goals through simple daily check-ins.</p>

      <h3>Key Features</h3>
      <ul>
        <li>🎯 Set and update your personal goal</li>
        <li>📅 Visual calendar with check marks</li>
        <li>🔥 Real-time streak counter</li>
        <li>🏅 Gamified levels based on your streak</li>
      </ul>

      <h3>Why Build Habits?</h3>
      <p>Small actions done consistently lead to big results. This app helps you stay on track and stay accountable.</p>
      <button type="button" id="closeAbout">close</button>
    </div>
  </div>

  <script>
    // bind
    openRules = document.getElementById('openRules');
    closeRules = document.getElementById('closeRules');

    openAbout = document.getElementById('openAbout');
    closeAbout = document.getElementById('closeAbout');
    // action
    openRules.addEventListener('click', function() {
      document.querySelector('.rulesModal').style.display = 'flex';
    });
    closeRules.addEventListener('click', function() {
      document.querySelector('.rulesModal').style.display = 'none';
    });
    openAbout.addEventListener('click', function() {
      document.querySelector('.aboutModal').style.display = 'flex';
    });
    closeAbout.addEventListener('click', function() {
      document.querySelector('.aboutModal').style.display = 'none';
    });
  </script>
</body>

</html>