
function trim(str) { //删除左右两端的空格
    return str.replace(/(^\s*)|(\s*$)/g, "");
}


$(document).keyup(function(event){
    if(event.keyCode ==13){
        $("#button").trigger("click");
    }
});



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
            console.log(res);
            var obj = JSON.parse(res);
            if (obj.code == 0) {
                window.location.href = "login.php";
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
