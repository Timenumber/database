<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["username"] !== "admin") {
    header("Location: ..\..\login\login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $major_id = $_POST["major_id"];
    $uni_id = $_POST["uni_id"];
    $major_name = $_POST["major_name"];
    $major_addr = $_POST["major_addr"];
    $major_qs = $_POST["major_qs"];
    $major_description = $_POST["major_description"];

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
    $sql = "UPDATE major SET major_name='$major_name', major_addr='$major_addr', major_qs='$major_qs', major_description='$major_description' 
            WHERE major_id='$major_id'";

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
