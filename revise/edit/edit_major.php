<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["username"] !== "admin") {
    header("Location: ..\..\login\login.php");
    exit();
}

if (isset($_GET["major_id"])) {
    $major_id = $_GET["major_id"];

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

    // 获取记录
    $sql = "SELECT uni_id, major_name, major_addr, major_qs, major_description FROM major WHERE major_id='$major_id'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $uni_id = $row["uni_id"];
        $major_name = $row["major_name"];
        $major_addr = $row["major_addr"];
        $major_qs = $row["major_qs"];
        $major_description = $row["major_description"];
    } else {
        echo "无效的专业ID";
        $conn->close();
        exit();
    }
    $conn->close();
} else {
    echo "无效的专业ID";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>修改专业</title>
</head>
<body>
    <h1>修改专业</h1>
    <form action="..\update\update_major.php" method="post">
        <input type="hidden" name="major_id" value="<?php echo htmlspecialchars($major_id); ?>">
        <label for="major_name">专业名称:</label>
        <input type="text" id="major_name" name="major_name" value="<?php echo htmlspecialchars($major_name); ?>" required>
        <br>
        <label for="major_addr">专业地址:</label>
        <input type="text" id="major_addr" name="major_addr" value="<?php echo htmlspecialchars($major_addr); ?>">
        <br>
        <label for="major_qs">专业QS排名:</label>
        <input type="number" id="major_qs" name="major_qs" value="<?php echo htmlspecialchars($major_qs); ?>">
        <br>
        <label for="major_description">专业描述:</label>
        <textarea id="major_description" name="major_description"><?php echo htmlspecialchars($major_description); ?></textarea>
        <br>
        <button type="submit">提交</button>
    </form>
</body>
</html>
