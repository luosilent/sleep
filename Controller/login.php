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
        $_SESSION['username'] = $post_name;
        $data['code'] = 0;
        $data['msg'] = "登录成功";
    } else {
        $data['code'] = 1;
        $data['msg'] = "用户名或密码不正确";
    }
} else {
    $data['code'] = 1;
    $data['msg'] = "用户未注册";
}
print_r(json_encode($data));
