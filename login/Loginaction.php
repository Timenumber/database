<?php
header("Content-Type: text/html;charset=utf-8");
session_start();

$username = isset($_POST['username']) ? $_POST['username'] : "";
$password = isset($_POST['password']) ? $_POST['password'] : "";

if (!empty($username) && !empty($password)) {
    // 建立连接
    $conn = mysqli_connect('localhost', 'Lab2admin', '123456', 'user');
    
    if (!$conn) {
        die("连接失败: " . mysqli_connect_error());
    }
    
    // 准备SQL语句
    $sql_select = "SELECT id, username, password FROM usertext WHERE username = '$username' AND password = '$password'";
    
    // 执行SQL语句
    $ret = mysqli_query($conn, $sql_select);
    $row = mysqli_fetch_array($ret);
    
    // 判断用户名或密码是否正确
    if ($username == $row['username'] && $password == $row['password']) {
        // 选中“记住我”
        if (isset($_POST['remember']) && $_POST['remember'] == "on") {
            // 创建cookie
            setcookie("user", $username, time() + 7 * 24 * 3600);
        }
        
        // 创建session
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $username;

        // 检查是否是管理员
        if ($username === 'admin' && $password === 'admin') {
            header("Location: ..\admin.php");
        } else {
            header("Location: ..\index.php");
        }
        
        // 关闭数据库连接
        mysqli_close($conn);
        exit();
    } else {
        // 用户名或密码错误，赋值err为1
        header("Location: login.php?err=1");
        exit();
    }
} else {
    // 用户名或密码为空，赋值err为2
    header("Location: login.php?err=2");
    exit();
}
?>
