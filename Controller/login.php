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

$login = getUser($post_name);
$pwd = $login['password'];
if ($login) {
    if (password_verify($post_pwd, $pwd)) {
        $conn = conn();
        $sql = "select username from member where username = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":username", $post_name);
        $result = $stmt->execute();
        if ($result) {
            $_SESSION['username'] = $post_name;
            $_SESSION['uid'] = $login['id'];

            setcookie("pwd", $pwd, time()+3600*24);
            $data['code'] = 0;
            $data['msg'] = "登录成功";
        }
    } else {
        $data['code'] = 1;
        $data['msg'] = "用户名或密码不正确";
    }
} else {
    $data['code'] = 1;
    $data['msg'] = "用户未注册";
}
print_r(json_encode($data));
