<?php
session_start();
if (isset($_COOKIE['username'])) {
    $username = $_COOKIE['username'];
} else {
    header("Location: login.php");
}
require 'Controller/get.php';
$user = getUser($username);
$uid = $user['id'];
$rank = $user['rank'];
$sign = getSign($uid);
$going = getGoing($uid);
$time = getTime($uid);
$userRank = getRank();
$uid = $user['id'];
$rank = $user['rank'];
$sleep_time = $user['sleep_time'];
$hour = substr($sleep_time,0,2);
$minute = substr($sleep_time,3,2);
$second = substr($sleep_time,6,2);
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
            <p class="name"><? echo $username ?></p>
            <p class="integral"><i class="fa fa-diamond"></i> 积分：<? echo $rank ?><p id="gold"></p></p>
            <p>已签到<span class="color-red"><? echo $sign ?></span>天</p>
            <!-- 如果已经签到 加class signed -->
            <a href="javascript:void(0)" class="btn-red btn-sign" id="btnSign" title="每天早睡签到时间20:00-0:00">点击签到</a>
        </div>
        <div class="sign-tips">
            每日早睡签到即得1积分，默认睡觉时间23:00
            <br>自定义睡觉时间：
                <input class="time" name="hour" id="hour" value="<? echo $hour ?>" />：
            <input class="time" name="minute" id="minute" value="<? echo $minute ?>"  />：
             <input class="time" name="second" id="second" value="<? echo $second ?>"/>
            <input type="button" id="submit" value="修改">
            <br><label id="warn" style="color: red;"></label>

            </form>
        </div>
        <div class="sign-calendar" id="calendarViews">

        </div>
        <div class="sign-days">
            <div class="days-tit">连续签到7日，可额外获得5个积分</div>
            <div class="days-bar">
                <span id="signNum0" class="num">0</span>
                <span id="signNum1" class="num">1</span>
                <span id="signNum2" class="num">2</span>
                <span id="signNum3" class="num">3</span>
                <span id="signNum4" class="num">4</span>
                <span id="signNum5" class="num">5</span>
                <span id="signNum6" class="num">6</span>
                <span id="signNum7" class="num">7</span>
                <span class="step"> <? echo $going ?>/7</span>
                <span class="bar"></span>
                <span class="loading" id="signBarLoading"></span>
            </div>
        </div>
    </div>

    <div class="sign-calendar-foot">
        <p>每天坚持早睡，身体好</p>
    </div>
</div>
<div class="sign-calendar-box2">
    <div class="sign-calendar-body2">
        <h3>每天签到时间</h3>
        <div class="sign-body2">
            <?php
                foreach ($time as $v){
                    if ($v['type'] == 1){
                        echo  "<li class='type1'>$v[post_time]</li>";
                    }else{
                        echo  "<li class='type2'>$v[post_time]</li>";
                    }
                    echo "<hr/>";
                }
            ?>
        </div>
    </div>
    <div class="line"></div>
    <div class="sign-calendar-body3">
        <h3>计划<? echo $sleep_time ?>睡觉</h3>
        <div class="sign-body2">
            <?php
            foreach ($time as $v){
                if ($v['type'] == 1){
                    echo  "<li class='type1'>$v[diff]</li>";
                }else {
                    echo "<li class='type2'>$v[diff]</li>";
                }
                echo "<hr/>";
            }
            ?>
        </div>
    </div>
</div>
<div class="sign-calendar-box3">
    <div class="sign-calendar-body2">
        <h3>早睡积分排行</h3>
        <div class="sign-body2">
            <?php
            foreach ($userRank as $v){
                    echo  "<li class='type1'>$v[id].$v[user]</li>";
                echo "<hr/>";
            }
            ?>
        </div>
    </div>
</div>
<script src="js/sign-calendar.js"></script>

<script type="text/javascript" src="js/jquery-3.3.1.js" charset="utf-8"></script>
<script>

    $(document).ready(function () {
        var uid = "<? echo $uid ?>";
        var username = "<? echo $username ?>";
        var going = "<? echo $going ?>";
        var signCalendar = new calendar();
        var d = signCalendar.getDay();
        var year = d.y;
        var month = d.m + 1;
        var today = d.d;
        $.ajax({
                type: "post",
                url: "Controller/get.php",
                data:'month='+month+'&uid='+uid,
                success: function (data) {
                var obj = JSON.parse(data);
                var days = Array();
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
                        going: going//签到持续天数<=7
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
                    data: 'is_sign=' + is_sign + '&year=' + year + '&month=' + month + '&day=' + today + '&uid=' + uid + '&username=' + username,
                    success: function (res) {
                        var gold = 1;
                       console.log(res);
                        if (going  == 7) {
                            $.ajax({
                                type: "POST",
                                url: "Controller/post.php",
                                data: 'gold=' + gold + '&username=' + username,
                                success: function () {
                                    $('#gold').addClass('gold').html("积分+5");
                                }
                            })
                        }
                        $btn.addClass('signing').attr('disabled', 'disabled').html("正在签到...");
                        // 延时2秒看效果
                        setTimeout(function () {
                            // 获取年月日 方法: signCalendar.getDay() ;
                            // 签到成功效果
                            $btn.removeClass('signing').addClass('signed').html("已签到");
                            signCalendar.play(d.d);
                        }, 2000);
                        window.location.reload();
                    }
                });

        });

        // 修改时间
        $('#submit').on('click',function () {
            var $btn = $(this);
            var hour = $("#hour").val();
            var minute = $("#minute").val();
            var second = $("#second").val();
            var gold = 2;
            if (hour>24 || hour<18){
                $('#warn').html("只能在18-24小时之间");
                return
            }
            if (minute>=60 || minute <0){
                $('#warn').html("只能在00-60分钟之间");
                return
            }
            if (second>=60 || second <0){
                $('#warn').html("只能在00-60秒之间");
                return
            }
                $.ajax({
                    type: "POST",
                    url: "Controller/post.php",
                    data: 'gold=' + gold + '&username=' + username + '&hour=' + hour + '&minute=' + minute + '&second=' + second,
                    success: function (res) {
                        $btn.val("修改成功");
                        setTimeout(window.location.reload.bind(window.location), 1000);
                        }

                })
        });
    })

</script>
</body>
</html>