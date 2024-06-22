<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: ..\..\login\login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $apply_id = $_POST["apply_id"];
    $year = $_POST["year"];
    $process = $_POST["process"];
    $feelings = $_POST["feelings"];
    $uni_id = $_POST["uni_id"];
    $major_id = $_POST["major_id"];

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

    if ($major_id >= 0 && $uni_id >= 0) {
        // 检查专业是否存在
        $sql = "SELECT * FROM major WHERE major_id = $major_id AND uni_id = $uni_id";
        $result = $conn->query($sql);
        if ($result->num_rows == 0) {
            echo "学校和专业对不存在";
            exit();
        }
    } else {
        echo "无效的学校和专业对";
        exit();
    }
    // 更新记录
    $sql = "UPDATE applylist SET year='$year', process='$process', feelings='$feelings', uni_id='$uni_id', major_id='$major_id' 
            WHERE apply_id='$apply_id' AND user_id='" . $_SESSION["user_id"] . "'";

    if ($conn->query($sql) === TRUE) {
        echo "记录更新成功";
        header("Location: ..\..\index.php");
        exit();
    } else {
        echo "更新记录错误: " . $conn->error;
    }

    $conn->close();
} else {
    echo "无效的请求";
}
?>
