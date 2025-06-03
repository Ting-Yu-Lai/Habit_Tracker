<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habit-Tracker Login</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;

        }

        body {
            height: 100vh;
            background-image: url(./images/login_bg.jpg);
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
        }

        header {
            font-weight: bold;
            color: white;
            height: 10%;
        }

        .login-box {
            width: 400px;
            height: 60vh;
            background-color: rgba(0, 0, 0, 0.6);
            border-radius: 10px;
            backdrop-filter: blur(5px);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }



        .acc-pw-box {
            height: 30%;
            width: 100%;
            /* background-color: #fff; */

        }

        form {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .acc-box,
        .pw-box {
            width: 80%;
            height: 33%;
            /* 預留一點間隔 */
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        input {
            width: 100%;
            height: 75%;
            border-radius: 50px;
            padding-left: 20px;
            margin: auto;
            border: 0.5px solid white;
            background-color: transparent;
            color: white;
            outline: 0.5px solid white;
        }

        button {
            width: 80%;
            height: 25%;
            border-radius: 50px;
            margin-top: 20px;
            border: 0.5px solid white;
            background-color: white;
            color: black;
            outline: 0.5px solid white;
            text-align: center;
        }

        button:hover {
            background-color: transparent;

        }

        footer {
            margin-top: 20px;
        }

        a {
            font-weight: bold;
            color: white;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <?php
    // 啟動session
    session_start();
    ?>

    <!-- 登入畫面 login-box -->
    <div class="login-box">
        <header>
            <h1>Login</h1>
        </header>
        <div class="acc-pw-box">
            <form action="check.php" method="post">
                <div class="acc-box">
                    <input type="text" name="account" id="" placeholder="Username">
                </div>
                <div class="pw-box">
                    <input type="text" name="password" id="" placeholder="Password">
                </div>
                <button type="submit">Login</button>

            </form>

        </div>

        <footer>
            Don't have an account? <a href="./signup.php">Signup</a>
        </footer>

        </form>

        <!-- 登入失敗提示 -->
        <?php
            if(isset($_SESSION['login'])) {
                
            }
        
            if (isset($_SESSION['error'])) {
                echo "<p style='color:red'>" . $_SESSION['error'] . "</p>";
                unset($_SESSION['error']); // 顯示完清除錯誤訊息
            }
        ?>
    </div>


</body>

</html>