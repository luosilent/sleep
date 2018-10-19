<?php
require '../Connect/conn.php';
session_start();

$conn = conn();
$sql = "update member set islogin = '0' where id = :id";
$stmt = $conn->prepare($sql);

$stmt->bindParam(":id", $_SESSION['uid']);
$res = $stmt->execute();

if ($res) {
    session_destroy();
    $data['code'] = 0;
    $data['msg'] = "退出成功";
} else {
    $data['code'] = 1;
    $data['msg'] = "退出失败";
}
print_r(json_encode($data));
