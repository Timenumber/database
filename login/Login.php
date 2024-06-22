<!DOCTYPE html>
<html>
<head>
    <title>登录</title>
    <link rel="stylesheet" href="login.css">
    <meta charset="UTF-8">
</head>
<body>
<div id="bigBox">
    <h1>登录页面</h1>
    <form id="loginform" action="loginaction.php" method="post">
        <div class="inputBox">
            <div class="inputText">
                <input type="text" id="name" name="username" placeholder="Username" value="">
            </div>
            <div class="inputText">
                <input type="password" id="password" name="password" placeholder="Password">
            </div>
            <br>
            <div style="color: white; font-size: 12px">
                <?php
                $err = isset($_GET["err"]) ? $_GET["err"] : "";
                switch ($err) {
                    case 1:
                        echo "用户名或密码错误！";
                        break;
                    case 2:
                        echo "用户名或密码不能为空！";
                        break;
                }
                ?>
            </div>
            <div class="register">
                <a href="../register/register.php" style="color: white">注册账号</a>
            </div>
            <div class="fgtpwd">
                <a href="#" style="color: white">忘记密码</a>
            </div>
        </div>
        <div>
            <input type="submit" id="login" name="login" value="登录" class="loginButton m-mid">
        </div>
    </form>
</div>
</body>
</html>
