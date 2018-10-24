<?php
session_start();
if (isset($_COOKIE['username'])) {
    $username = $_COOKIE['username'];
    $uid = $_COOKIE['uid'];
} else {
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html style="background:#666 !important;">
<head>
    <meta charset="UTF-8">
    <title>签到功能</title>
    <link rel="stylesheet" href="css/style.css">

</head>
<body style="background:#666 !important;">
<div class="sign-calendar-box">
    <div class="sign-calendar-body">
        <div class="sign-user">
            <a class="avatar " href="#" title="luosilent"><img src="img/kobe.jpg"><i class="fa fa-user fa-4x"></i></a>
            <p class="name"><?php echo $username ?></p>
            <p class="integral"><i class="fa fa-diamond"></i> 积分：506</p>
            <p>已连续签到<span class="color-red">305</span>天</p>
            <!-- 如果已经签到 加class signed -->
            <a href="javascript:void(0)" class="btn-red btn-sign" id="btnSign">点击签到</a>
        </div>
        <div class="sign-tips">
            每日签到即得1积分，连续签到更可额外领取更多积分
            <br>积分排名
        </div>
        <div class="sign-calendar" id="calendarViews">

        </div>
        <div class="sign-days">
            <div class="days-tit">连续签到7日，可额外获得5个积分</div>
            <div class="days-bar">
                <span id="signNum1" class="num">1</span>
                <span id="signNum2" class="num">2</span>
                <span id="signNum3" class="num">3</span>
                <span id="signNum4" class="num">4</span>
                <span id="signNum5" class="num">5</span>
                <span id="signNum6" class="num">6</span>
                <span id="signNum7" class="num">7</span>
                <span class="step">2/7</span>
                <span class="bar"></span>
                <span class="loading" id="signBarLoading"></span>
            </div>
            <a href="#" class="btn btn-red">领取积分</a>
        </div>
    </div>
    <div class="sign-calendar-foot">
        <p>每天坚持早睡，身体好</p>
    </div>
</div>
<script src="js/sign-calendar.js"></script>

<script type="text/javascript" src="js/jquery-3.3.1.js" charset="utf-8"></script>
<script>

    $(document).ready(function () {
        var uid = "<?php echo $uid ?>";
        var signCalendar = new calendar();
        var d = signCalendar.getDay();
        var year = d.y;
        var month = d.m + 1;
        var today = d.d;
        $.ajax({
                type: "post",
                url: "Controller/get.php",
                data:'month='+month,
                success: function (data) {
                var obj = JSON.parse(data);
                var days =new Array();
                $.each(obj, function (key, val) {
                    var day = val['day'];
                    var id = val['uid'];
                    var is_sign = val['is_sign'];
                    if (id == uid && is_sign == 1) {
                        days.push(day);
                    }
                    if (today == day && is_sign == 1){
                        var btnSign =  $("#btnSign");
                        btnSign.addClass('signed').html("已签到");
                    }
                });
                    var opt = {
                        id: 'calendarViews',//日历
                        signDays: days,//已经签到的日期[1,2,3]
                        going: 2//签到持续天数<=7
                    };
                    // 初始化日历
                    signCalendar.init(opt);
            },
            error:function () {
                console.log("gg");
            }
        });

        // 签到
        $('#btnSign').on('click',function () {
            var $btn = $(this);
            var is_sign = 1;
            if ($btn.hasClass('signed') || $btn.hasClass('signing')) return;
            $.ajax({
                type: "POST",
                url: "Controller/post.php",
                data: 'is_sign=' + is_sign + '&year=' + year + '&month=' + month + '&day=' + today + '&uid='+ uid,
                success: function () {
                    $btn.addClass('signing').attr('disabled', 'disabled').html("正在签到...");
                    // 延时两秒看效果
                    setTimeout(function () {
                        // 获取年月日 方法: signCalendar.getDay() ;
                        // 签到成功效果
                        $btn.removeClass('signing').addClass('signed').html("已签到");
                        signCalendar.play(d.d);
                    }, 1000);
                }
            })
        });
    })
</script>
</body>
</html>