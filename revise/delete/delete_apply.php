<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["username"] !== "admin") {
    header("Location: ..\..\login\login.php");
    exit();
}

if (isset($_GET["apply_id"])) {
    $apply_id = $_GET["apply_id"];

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

    // 删除记录
    $sql = "DELETE FROM applylist WHERE apply_id='$apply_id'";

    if ($conn->query($sql) === TRUE) {
        echo "记录删除成功";
    } else {
        echo "删除记录错误: " . $conn->error;
    }

    $conn->close();
    header("Location: ..\..\admin.php");
    exit();
} else {
    echo "无效的申请ID";
}
?>
