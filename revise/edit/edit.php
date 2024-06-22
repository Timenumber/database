<?php
session_start();
if (!isset($_SESSION["user_id"])) {
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
    
    // 获取记录
    $sql = "SELECT year, process, feelings, uni_id, major_id FROM applylist WHERE apply_id='$apply_id' AND user_id='" . $_SESSION["user_id"] . "'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $year = $row["year"];
        $process = $row["process"];
        $feelings = $row["feelings"];
        $uni_id = $row["uni_id"];
        $major_id = $row["major_id"];
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
    <title>修改申请记录</title>
</head>
<body>
    <h1>修改申请记录</h1>
    <form action="..\update\update.php" method="post">
        <input type="hidden" name="apply_id" value="<?php echo htmlspecialchars($apply_id); ?>">
        <label for="uni_id">申请学校ID:</label>
        <input type="text" id="uni_id" name="uni_id" value="<?php echo htmlspecialchars($uni_id); ?>" required>
        <br>
        <label for="major_id">申请专业ID:</label>
        <input type="text" id="major_id" name="major_id" value="<?php echo htmlspecialchars($major_id); ?>" required>
        <br>
        <label for="year">年份:</label>
        <input type="text" id="year" name="year" value="<?php echo htmlspecialchars($year); ?>" required>
        <br>
        <label for="process">申请过程:</label>
        <textarea id="process" name="process" required><?php echo htmlspecialchars($process); ?></textarea>
        <br>
        <label for="feelings">感想:</label>
        <textarea id="feelings" name="feelings" required><?php echo htmlspecialchars($feelings); ?></textarea>
        <br>
        <button type="submit">提交</button>
    </form>
</body>
</html>
