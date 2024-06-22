<?php
header("Content-Type: text/html;charset=utf-8");

$username = isset($_POST['username']) ? $_POST['username'] : "";
$password = isset($_POST['password']) ? $_POST['password'] : "";
$re_password = isset($_POST['re_password']) ? $_POST['re_password'] : "";
$sex = isset($_POST['sex']) ? $_POST['sex'] : "";
$qq = isset($_POST['qq']) ? $_POST['qq'] : "";
$email = isset($_POST['email']) ? $_POST['email'] : "";
$phone = isset($_POST['phone']) ? $_POST['phone'] : "";
$address = isset($_POST['address']) ? $_POST['address'] : "";

if ($password == $re_password) { 
    $conn = mysqli_connect('localhost', 'Lab2admin', '123456', 'user'); 
    mysqli_set_charset($conn,"utf8");
    $sql_select = "SELECT username FROM usertext WHERE username = '$username'"; 
    $ret = mysqli_query($conn, $sql_select);
    $row = mysqli_fetch_array($ret); 
    if ($username == $row['username']) { 
        header("Location: register.php?err=1");
    } else { 
        $sql_insert = "INSERT INTO usertext(username,password,sex,qq,email,phone,address) VALUES('$username','$password','$sex','$qq','$email','$phone','$address')"; //执行SQL语句
        mysqli_query($conn, $sql_insert);
        header("Location: ..\..\index.php");
    } //关闭数据库
    mysqli_close($conn);
} else {
    header("Location: register.php?err=2");
} ?>

