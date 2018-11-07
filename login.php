<?php
session_start();
if (isset($_SESSION['username'])) {
    setcookie("username", $_SESSION['username'], time()+3600*24*30);
    setcookie("uid", $_SESSION['uid'], time()+3600*24*30);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>早睡签到</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="css/main.css" rel="stylesheet" type="text/css"/>
    <script src="js/jquery-3.3.1.js"></script>
    <script src="js/login.js"></script>

</head>
<body>
<div class="login-box">

    <!-- /.login-logo -->
    <div class="login-box-body">
        <div class="login-logo" style="margin-bottom: 15px ">
            <img style="width: 400px" src="img/logo.gif"/>
        </div>
        <form>
            <div class="form-group has-feedback">
                <input type="text" class="form-control" placeholder="用户名" name="name"/>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" placeholder="密码" name="pwd" autocomplete="on"/>
            </div>

            <div class="row">
                <input type="button" class="btn btn-primary btn-block btn-flat" id="button" value="登录"/>
                <input type="button" style="margin-right: 10px;" class="btn btn-primary btn-block btn-flat"
                       id="register"
                       value="注册"/>
            </div>
        </form>
    </div>
</div>
<div class="common_footer">
    Powered by luosilent.top | Copyright © All rights reserved.
</div>

</body>
</html>