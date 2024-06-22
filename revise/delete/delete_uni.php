<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["username"] !== "admin") {
    header("Location: ..\..\login\login.php");
    exit();
}

if (isset($_GET["uni_id"])) {
    $uni_id = $_GET["uni_id"];

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

    // 调用存储过程删除学校及相关记录
    $sql = "CALL delete_university(?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $uni_id);

    if ($stmt->execute()) {
        echo "记录删除成功";
    } else {
        echo "删除记录错误: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    header("Location: ..\..\admin.php");
    exit();
} else {
    echo "无效的学校ID";
}
?>
