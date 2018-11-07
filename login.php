<?php

session_start();
if (isset($_SESSION['username'])) {
    setcookie("username", $_SESSION['username'], time()+3600*24*30);
    setcookie("uid", $_SESSION['uid'], time()+3600*24*30);
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>早睡签到</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="css/main.css" rel="stylesheet" type="text/css"/>
    <script src="js/jquery-3.3.1.js"></script>
    <script>
        function trim(str) { //删除左右两端的空格
            return str.replace(/(^\s*)|(\s*$)/g, "");
        }

        function login() {
            var name = trim($("input[name='name']").val());
            var pwd = trim($("input[name='pwd']").val());
            if (name == '') {
                alert("请填写用户名");
                return;
            }
            if (pwd == '') {
                alert("请填写密码");
                return;
            }
            $.ajax({
                url: "Controller/login.php",
                type: "post",
                data: {"name": name, "pwd": pwd},
                success: function (res) {
                    var obj = JSON.parse(res);
                    if (obj.code == 0) {
                        window.location.href = "sign.php";
                    } else if (obj.code == 1) {
                        alert(obj.msg);
                    }
                }
            });
        }

        function register() {
            var name = trim($("input[name='name']").val());
            var pwd = trim($("input[name='pwd']").val());
            if (name == '') {
                alert("请填写用户名");
                return;
            }
            if (pwd == '') {
                alert("请填写密码");
                return;
            }
            $.ajax({
                url: "Controller/register.php",
                type: "post",
                data: {"name": name, "pwd": pwd},
                success: function (res) {
                    var obj = JSON.parse(res);
                    if (obj.code == 0) {
                        window.location.href = "sign.php";
                    } else if (obj.code == 1) {
                        alert(obj.msg);
                    }
                }
            });
        }

        $(function () {
            $("#button").click(function () {
                login();
            });
            $("#register").click(function () {
                register();
            });
        });
    </script>
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