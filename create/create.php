<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: ..\login\login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["user_id"];
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

    // 获取当前最大apply_id
    $sql_max_id = "SELECT MAX(apply_id) AS max_id FROM applylist";
    $result_max_id = $conn->query($sql_max_id);
    $row_max_id = $result_max_id->fetch_assoc();
    $apply_id = $row_max_id['max_id'] + 1;

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
    if ($major_id >= 0) {
        // 检查专业是否存在
        $sql = "SELECT * FROM major WHERE major_id = $major_id";
        $result = $conn->query($sql);
        if ($result->num_rows == 0) {
            echo "专业不存在";
            exit();
        }
    } else {
        echo "无效的专业ID";
        exit();
    }
    // 插入记录
    $sql = "INSERT INTO applylist (apply_id, user_id, uni_id, major_id, year, process, feelings) 
            VALUES ('$apply_id', '$user_id', '$uni_id', '$major_id', '$year', '$process', '$feelings')";

    if ($conn->query($sql) === TRUE) {
        echo "新记录插入成功";
        header("Location: ..\index.php");
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
    <title>新建申请记录</title>
    <style>
        .logout {
            position: absolute;
            right: 10px;
            top: 10px;
        }
        .login {
            position: absolute;
            right: 10px;
            top: 10px;
        }
        .university-logo {
            width: 50px;
            height: 50px;
        }
    </style>
</head>
<body>
    <h1>新建申请记录</h1>
    <form action="create.php" method="post">
        <label for="year">年份:</label>
        <input type="text" id="year" name="year" required>
        <br>
        <label for="process">申请过程:</label>
        <textarea id="process" name="process" required></textarea>
        <br>
        <label for="feelings">感想:</label>
        <textarea id="feelings" name="feelings" required></textarea>
        <br>
        <label for="uni_id">申请学校ID:</label>
        <input type="text" id="uni_id" name="uni_id" required>
        <br>
        <label for="major_id">申请专业ID:</label>
        <input type="text" id="major_id" name="major_id" required>
        <br>
        <button type="submit">提交</button>
    </form>

    <h2>专业列表</h2>
<table border="1">
    <thead>
        <tr>
            <th>专业ID</th>
            <th>学校名称</th>
            <th>专业名称</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // MySQL连接部分
        $servername = "localhost";
        $username_db = "Lab2admin";
        $password_db = "123456";
        $dbname = "user";

        // 创建连接
        $conn = new mysqli($servername, $username_db, $password_db, $dbname);
        
        // 查询专业记录
        
        $sql = "SELECT major_id, major_name, uni_id FROM major ORDER BY uni_id, major_id ASC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // 输出数据
            while($row = $result->fetch_assoc()) {
                $uni_sql = "SELECT uni_name FROM university WHERE uni_id='" . $row["uni_id"] . "'";
                $uni_result = $conn->query($uni_sql);
                $uni_row = $uni_result->fetch_assoc();
                $university_name = $uni_row['uni_name'];

                echo "<tr>
                        <td>" . $row["major_id"] . "</td>
                        <td>" . htmlspecialchars($university_name) . "</td>
                        <td>" . htmlspecialchars($row["major_name"]) . "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='3'>没有记录</td></tr>";
        }
        ?>
    </tbody>
</table>
<h2>大学列表 (按QS排名)</h2>
<table border="1">
    <thead>
        <tr>
            <th>校徽</th>
            <th>学校ID</th>
            <th>学校名称</th>
            <th>QS排名</th>
            <th>申请人数</th>
        </tr>
    </thead>
    <tbody>
        <?php
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

        // 查询大学记录并按QS排名排序
        $sql = "SELECT uni_id, uni_name, picture_addr, qs_rank, apply_cnt FROM university ORDER BY qs_rank ASC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // 输出数据
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td><img src='../uimage/" . htmlspecialchars($row["picture_addr"]) . "' class='university-logo' alt='" . htmlspecialchars($row["uni_name"]) . "'></td>
                        <td>" . $row["uni_id"] . "</td>
                        <td>" . htmlspecialchars($row["uni_name"]) . "</td>
                        <td>" . $row["qs_rank"] . "</td>
                        <td>" . $row["apply_cnt"] . "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='3'>没有记录</td></tr>";
        }
        ?>
    </tbody>
</table>
</body>
</html>
