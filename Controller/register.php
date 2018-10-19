<?php
require '../Connect/conn.php';
session_start();
if (isset($_POST["name"])) {
    $post_name = $_POST["name"];
    $post_pwd = $_POST["pwd"];
} else {
    $post_name = "";
    $post_pwd = "";
}
$register = getUser($post_name);

if ($register){
    $data['code'] = 1;
    $data['msg'] = "用户名已被注册";
}else {
    $pwd = password_hash($post_pwd, PASSWORD_DEFAULT);
    $conn = conn();
    $one = 1;
    $sql = "INSERT INTO member (`username`,`password`) VALUES (:username,:password)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":username", $post_name);
    $stmt->bindParam(":password", $pwd);
    $result = $stmt->execute();
    $register = getUser($post_name);
    if ($result) {
        $_SESSION['username'] = $register['username'];
        $_SESSION['uid'] = $register['id'];
        $data['code'] = 0;
        $data['msg'] = "注册成功";
    } else {
        $data['code'] = 1;
        $data['msg'] = "注册失败";
    }
}
print_r(json_encode($data));
