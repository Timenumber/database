<?php
session_start();
$user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;
$username = isset($_SESSION["username"]) ? $_SESSION["username"] : null;
if ($_SESSION["username"] == "admin") {
    header("Location: admin.php");
    exit();
}

// 搜索条件
$search = isset($_GET['search']) ? $_GET['search'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>申请记录列表</title>
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

<?php if ($user_id): ?>
    <div class="logout">
        欢迎你, <?php echo htmlspecialchars($username); ?>! 
        <a href="../login/logout.php">退出登录</a>
    </div>
<?php else: ?>
    <div class="login">
        <a href="../login/login.php">登录</a>
    </div>
<?php endif; ?>

<h1>申请记录列表</h1>

<!-- 搜索表单 -->
<form method="get" action="index.php">
    <label for="search">搜索:</label>
    <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">搜索</button>
</form>

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

// 获取统计数据
$sql_total_applications = "SELECT get_total_applications() AS total_applications";
$sql_total_universities = "SELECT get_total_universities() AS total_universities";
$sql_total_majors = "SELECT get_total_majors() AS total_majors";

$result_total_applications = $conn->query($sql_total_applications);
$result_total_universities = $conn->query($sql_total_universities);
$result_total_majors = $conn->query($sql_total_majors);

$total_applications = $result_total_applications->fetch_assoc()['total_applications'];
$total_universities = $result_total_universities->fetch_assoc()['total_universities'];
$total_majors = $result_total_majors->fetch_assoc()['total_majors'];

$conn->close();
?>
<?php if ($user_id): ?>
    <a href="create/create.php">新建申请记录</a>
<?php endif; ?>

<h2>申请记录</h2>
<ul>
    <li>总提交申请数: <?php echo $total_applications; ?></li>
</ul>
<table border="1">
    <thead>
        <tr>
            <th>申请ID</th>
            <th>用户</th>
            <th>学校</th>
            <th>专业</th>
            <th>年份</th>
            <th>申请过程</th>
            <th>感想</th>
            <th>操作</th>
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
        
        // 查询记录并按最新提交顺序展示
        $sql = "SELECT applylist.apply_id, applylist.user_id, applylist.uni_id, applylist.major_id, applylist.year, applylist.process, applylist.feelings, 
                       usertext.username, university.uni_name, major.major_name 
                FROM applylist
                JOIN usertext ON applylist.user_id = usertext.id
                JOIN university ON applylist.uni_id = university.uni_id
                JOIN major ON applylist.major_id = major.major_id";

        // 如果有搜索条件，添加到查询语句中
        if (!empty($search)) {
            $sql .= " WHERE usertext.username LIKE '%$search%' 
                      OR university.uni_name LIKE '%$search%'
                      OR major.major_name LIKE '%$search%'
                      OR applylist.year LIKE '%$search%'";
        }

        $sql .= " ORDER BY applylist.apply_id DESC";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // 输出数据
            while($row = $result->fetch_assoc()) {
                $process_preview = mb_substr($row["process"], 0, 10) . '...';
                $feelings_preview = mb_substr($row["feelings"], 0, 10) . '...';

                echo "<tr>
                        <td>" . $row["apply_id"] . "</td>
                        <td>" . htmlspecialchars($row["username"]) . "</td>
                        <td>" . htmlspecialchars($row["uni_name"]) . "</td>
                        <td>" . htmlspecialchars($row["major_name"]) . "</td>
                        <td>" . $row["year"] . "</td>
                        <td>" . $process_preview . "</td>
                        <td>" . $feelings_preview . "</td>
                        <td>
                            <a href='view_apply.php?apply_id=" . $row["apply_id"] . "'>查看</a>
                        </td>   
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='8'>没有记录</td></tr>";
        }
        $conn->close();
        ?>
    </tbody>
</table>
<h2>大学列表 (按QS排名)</h2>
<ul>
<li>学校数: <?php echo $total_universities; ?></li>
</ul>
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
                        <td><img src='uimage/" . htmlspecialchars($row["picture_addr"]) . "' class='university-logo' alt='" . htmlspecialchars($row["uni_name"]) . "'></td>
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
<h2>专业列表</h2>
<ul>
    <li>专业数: <?php echo $total_majors; ?></li>
</ul>
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

<?php if ($user_id): ?>
    <h2>我的申请记录</h2>
    <table border="1">
        <thead>
            <tr>
                <th>申请ID</th>
                <th>学校</th>
                <th>专业</th>
                <th>年份</th>
                <th>申请过程</th>
                <th>感想</th>
                <th>操作</th>
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
            // 查询当前用户的记录并按最新提交顺序展示
            $sql = "SELECT apply_id, uni_id, major_id, year, process, feelings FROM applylist WHERE user_id='$user_id' ORDER BY apply_id DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // 输出数据
                while($row = $result->fetch_assoc()) {
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

                    $process_preview = mb_substr($row["process"], 0, 10) . '...';
                    $feelings_preview = mb_substr($row["feelings"], 0, 10) . '...';

                    echo "<tr>
                            <td>" . $row["apply_id"] . "</td>
                            <td>" . htmlspecialchars($university_name) . "</td>
                            <td>" . htmlspecialchars($major_name) . "</td>
                            <td>" . $row["year"] . "</td>
                            <td>" . $process_preview . "</td>
                            <td>" . $feelings_preview . "</td>
                            <td>
                                <a href='view_apply.php?apply_id=" . $row["apply_id"] . "'>查看</a> |
                                <a href='revise/edit/edit.php?apply_id=" . $row["apply_id"] . "'>修改</a> |
                                <a href='revise/delete/delete.php?apply_id=" . $row["apply_id"] . "' onclick='return confirm(\"确认删除这条记录吗？\");'>删除</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>没有记录</td></tr>";
            }
            $conn->close();
            ?>
        </tbody>
    </table>
<?php endif; ?>



</body>
</html>
