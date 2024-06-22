<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: .\login\login.php");
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

    // 获取申请记录的详细信息
    $sql = "SELECT * FROM applylist WHERE apply_id='$apply_id'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // 获取用户姓名
        $user_sql = "SELECT username FROM usertext WHERE id='" . $row["user_id"] . "'";
        $user_result = $conn->query($user_sql);
        $user_row = $user_result->fetch_assoc();
        $username = $user_row['username'];

        // 获取学校名称
        $uni_sql = "SELECT uni_name FROM university WHERE uni_id='" . $row["uni_id"] . "'";
        $uni_result = $conn->query($uni_sql);
        $uni_row = $uni_result->fetch_assoc();
        $university_name = $uni_row['uni_name'];

        // 获取专业名称
        $major_sql = "SELECT major_name FROM major WHERE major_id='" . $row["major_id"] . "'";
        $major_result = $conn->query($major_sql);
        $major_row = $major_result->fetch_assoc();
        $major_name = $major_row['major_name'];
    } else {
        echo "无效的申请ID";
        $conn->close();
        exit();
    }
    $conn->close();
} else {
    echo "无效的申请ID";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>查看申请记录</title>
</head>
<body>
    <h1>申请记录详细信息</h1>
    <table border="1">
        <tr>1
            <th>申请ID</th>
            <td><?php echo htmlspecialchars($row["apply_id"]); ?></td>
        </tr>
        <tr>
            <th>用户</th>
            <td><?php echo htmlspecialchars($username); ?></td>
        </tr>
        <tr>
            <th>学校</th>
            <td><?php echo htmlspecialchars($university_name); ?></td>
        </tr>
        <tr>
            <th>专业</th>
            <td><?php echo htmlspecialchars($major_name); ?></td>
        </tr>
        <tr>
            <th>年份</th>
            <td><?php echo htmlspecialchars($row["year"]); ?></td>
        </tr>
        <tr>
            <th>申请过程</th>
            <td><?php echo nl2br(htmlspecialchars($row["process"])); ?></td>
        </tr>
        <tr>
            <th>感想</th>
            <td><?php echo nl2br(htmlspecialchars($row["feelings"])); ?></td>
        </tr>
    </table>
    <br>
    <a href="index.php">返回申请记录列表</a>
</body>
</html>
