<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["username"] !== "admin") {
    header("Location: ../../login/login.php");
    exit();
}

if (isset($_GET["uni_id"])) {
    $uni_id = $_GET["uni_id"];
} else {
    echo "无效的学校ID";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $target_dir = "uimage/";
        $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $allowed_types = array("jpg", "png", "jpeg", "gif");

        if (in_array($imageFileType, $allowed_types)) {
            $new_filename = $uni_id . '.' . $imageFileType;
            $target_file = $target_dir . $new_filename;
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                echo "图片上传成功.";

                // 更新数据库
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
                
                $sql = "UPDATE university SET picture_addr='$new_filename' WHERE uni_id='$uni_id'";
                if ($conn->query($sql) === TRUE) {
                    echo "数据库更新成功";
                } else {
                    echo "数据库更新错误: " . $conn->error;
                }

                $conn->close();
                header("Location: admin.php");
                exit();
            } else {
                echo "上传错误.";
            }
        } else {
            echo "只允许上传 JPG, JPEG, PNG 和 GIF 文件.";
        }
    } else {
        echo "上传文件错误.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>上传图片</title>
</head>
<body>
    <h1>上传图片</h1>
    <form action="upload_image.php?uni_id=<?php echo $uni_id; ?>" method="post" enctype="multipart/form-data">
        <label for="image">选择图片:</label>
        <input type="file" id="image" name="image" required>
        <br>
        <button type="submit">上传</button>
    </form>
</body>
</html>
