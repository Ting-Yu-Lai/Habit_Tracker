<?php
$goal = '';


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

    .goal-bar {
      width: 100%;
      height: 10vh;
      /* background-color: lightpink; */
      padding: 10px
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
      /* margin: auto; */
      background-color: red;
    }

    .main-right {
      width: 25%;
      height: 65vh;
      background-color: lightgreen;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }

    .tracker-box {
      width: 70%;
      height: 30vh;
      background-color: blue;
      margin-bottom: 5px;
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
  </style>
</head>

<body>
  <!-- header -->
  <header>
    <h1>Habit Tracker</h1>
    <div class="header-right">
      <div>Rules</div>
      <span>/</span>
      <div>About</div>
    </div>
  </header>

  <!-- goal bar -->
  <div class="goal-bar">
    <input type="text" name="goal" id="" placeholder="Write down your goals">
    <div class="goal-text">I'm a person who really loves $goal </div>
  </div>

  <!-- main -->
  <div class="main">
    <div class="main-left"></div>
    <div class="main-right">
      <div class="tracker-box"></div>
      <div class="medal-box"></div>
    </div>
  </div>

  <!-- footer -->
  <footer>
    © 2025 Akuma-Yu.
  </footer>
</body>

</html>