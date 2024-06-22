<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["username"] !== "admin") {
    header("Location: ..\..\login\login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $username = $_POST["username"];

    // MySQL连接部分
    $servername = "localhost";
    $username_db = "Lab2admin";
    $password_db = "123456";
    $dbname = "user";

    // 创建连接
    $conn = new mysqli($servername, $username_db, $password_db, $dbname);

    // 检查连接
    if ($conn->connect_error) {
        die("连接失败: " . $conn->connect_error);
    }

    // 更新记录
    $sql = "UPDATE usertext SET username='$username' WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "记录更新成功";
        header("Location: ..\..\admin.php");
        exit();
    } else {
        echo "更新记录错误: " . $conn->error;
    }

    $conn->close();
} else {
    echo "无效的请求";
}
?>
