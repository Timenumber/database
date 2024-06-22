<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["username"] !== "admin") {
    header("Location: ..\login\login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // 获取当前最大的 major_id
    $sql = "SELECT MAX(major_id) AS max_id FROM major";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $max_id = $row['max_id'];
    $new_major_id = $max_id + 1;

    if ($uni_id >= 0) {
        // 检查学校是否存在
        $sql = "SELECT * FROM university WHERE uni_id = $uni_id";
        $result = $conn->query($sql);
        if ($result->num_rows == 0) {
            echo "学校不存在";
            exit();
        }
    } else {
        echo "无效的学校ID";
        exit();
    }
    // 插入记录
    $sql = "INSERT INTO major (major_id, uni_id, major_name, major_addr, major_qs, major_description) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssds", $new_major_id, $uni_id, $major_name, $major_addr, $major_qs, $major_description);

    if ($stmt->execute()) {
        echo "新专业插入成功";
        header("Location: ..\admin.php");
        exit();
    } else {
        echo "错误: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>新增专业</title>
</head>
<body>
    <h1>新增专业</h1>
    <form action="create_major.php" method="post">
        <label for="uni_id">学校ID:</label>
        <input type="text" id="uni_id" name="uni_id" required>
        <br>
        <label for="major_name">专业名称:</label>
        <input type="text" id="major_name" name="major_name" required>
        <br>
        <label for="major_addr">专业地址:</label>
        <input type="text" id="major_addr" name="major_addr">
        <br>
        <label for="major_qs">专业QS排名:</label>
        <input type="number" id="major_qs" name="major_qs">
        <br>
        <label for="major_description">专业描述:</label>
        <textarea id="major_description" name="major_description"></textarea>
        <br>
        <button type="submit">提交</button>
    </form>
</body>
</html>
