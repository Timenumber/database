<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["username"] !== "admin") {
    header("Location: ..\..\login\login.php");
    exit();
}

if (isset($_GET["id"])) {
    $id = $_GET["id"];

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
    $sql = "SELECT username FROM usertext WHERE id='$id'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $username = $row["username"];
    } else {
        echo "无效的用户ID";
        $conn->close();
        exit();
    }
    $conn->close();
} else {
    echo "无效的用户ID";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>修改用户信息</title>
</head>
<body>
    <h1>修改用户信息</h1>
    <form action="..\update\update_user.php" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
        <label for="username">用户名:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
        <br>
        <button type="submit">提交</button>
    </form>
</body>
</html>
