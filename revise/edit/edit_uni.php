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
    
    // 获取记录
    $sql = "SELECT uni_name, physical_addr, web_addr, qs_rank FROM university WHERE uni_id='$uni_id'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $uni_name = $row["uni_name"];
        $physical_addr = $row["physical_addr"];
        $web_addr = $row["web_addr"];
        $qs_rank = $row["qs_rank"];
    } else {
        echo "无效的学校ID";
        $conn->close();
        exit();
    }
    $conn->close();
} else {
    echo "无效的学校ID";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>修改学校</title>
</head>
<body>
    <h1>修改学校</h1>
    <form action="..\update\update_uni.php" method="post">
        <input type="hidden" name="uni_id" value="<?php echo htmlspecialchars($uni_id); ?>">
        <label for="uni_name">学校名称:</label>
        <input type="text" id="uni_name" name="uni_name" value="<?php echo htmlspecialchars($uni_name); ?>" required>
        <br>
        <label for="physical_addr">实际地址:</label>
        <input type="text" id="physical_addr" name="physical_addr" value="<?php echo htmlspecialchars($physical_addr); ?>">
        <br>
        <label for="web_addr">网站地址:</label>
        <input type="text" id="web_addr" name="web_addr" value="<?php echo htmlspecialchars($web_addr); ?>">
        <br>
        <label for="qs_rank">QS排名:</label>
        <input type="number" id="qs_rank" name="qs_rank" value="<?php echo htmlspecialchars($qs_rank); ?>">
        <br>
        <button type="submit">提交</button>
    </form>
</body>
</html>
