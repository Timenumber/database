<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["username"] !== "admin") {
    header("Location: ..\login\login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // 获取当前最大的uni_id
    $sql = "SELECT MAX(uni_id) AS max_id FROM university";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $max_id = $row['max_id'];
    $uni_id = $max_id + 1;
    $picture_addr = "../uimage/" . $uni_id . '.' . $imageFileType;
    
    // 插入记录
    $sql = "INSERT INTO university (uni_id, uni_name, picture_addr, physical_addr, web_addr, qs_rank) 
            VALUES ('$uni_id', '$uni_name', '$picture_addr', '$physical_addr', '$web_addr', '$qs_rank')";

    if ($conn->query($sql) === TRUE) {
        echo "新学校插入成功";
        header("Location: ..\admin.php");
        exit();
    } else {
        echo "错误: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>新增学校</title>
</head>
<body>
    <h1>新增学校</h1>
    <form action="create_uni.php" method="post">
        <label for="uni_name">学校名称:</label>
        <input type="text" id="uni_name" name="uni_name" required>
        <br>
        <label for="physical_addr">实际地址:</label>
        <input type="text" id="physical_addr" name="physical_addr">
        <br>
        <label for="web_addr">网站地址:</label>
        <input type="text" id="web_addr" name="web_addr">
        <br>
        <label for="qs_rank">QS排名:</label>
        <input type="number" id="qs_rank" name="qs_rank">
        <br>
        <button type="submit">提交</button>
    </form>
</body>
</html>
