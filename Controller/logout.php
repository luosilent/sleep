<?php
session_start();

session_destroy();
$data['code'] = 0;
$data['msg'] = "退出成功";

print_r(json_encode($data));
