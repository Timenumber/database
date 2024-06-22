<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["username"] !== "admin") {
    header("Location: ..\..\login\login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uni_id = $_POST["uni_id"];
    $uni_name = $_POST["uni_name"];
    $physical_addr = $_POST["physical_addr"];
    $web_addr = $_POST["web_addr"];
    $qs_rank = $_POST["qs_rank"];

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
    $sql = "UPDATE university SET uni_name='$uni_name', physical_addr='$physical_addr', web_addr='$web_addr', qs_rank='$qs_rank' 
            WHERE uni_id='$uni_id'";

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
