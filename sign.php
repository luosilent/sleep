<?php
require 'Controller/get.php';
session_start();
if (isset($_COOKIE['username'])) {
    $username = $_COOKIE['username'];
} else {
    header("Location: login.php");
}
$username = $_COOKIE['username'];
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
$hour = substr($sleep_time, 0, 2);
$minute = substr($sleep_time, 3, 2);
$second = substr($sleep_time, 6, 2);
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
            <a href="javascript:void(0)" class="btn-red btn-sign" id="btnSign" title="每天签到时间20:00-24:00">点击签到</a>
        </div>
        <div class="sign-tips">
            每日早睡签到即得1积分，默认睡觉时间23:00
            <br>自定义睡觉时间：
            <input class="time" name="hour" id="hour" maxlength="2" onkeyup="this.value=this.value.replace(/\D/g,'')"
                   value="<? echo $hour ?>"/ >：
            <input class="time" name="minute" id="minute" maxlength="2"
                   onkeyup="this.value=this.value.replace(/\D/g,'')" value="<? echo $minute ?>"/>：
            <input class="time" name="second" id="second" maxlength="2"
                   onkeyup="this.value=this.value.replace(/\D/g,'')" value="<? echo $second ?>"/>
            <input type="button" id="submit" value="修改">
            <br>
            <div class="warn"><label id="warn"></label></div>

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
            foreach ($time as $v) {
                if ($v['type'] == 1) {
                    echo "<li class='type1'>$v[post_time]</li>";
                } else {
                    echo "<li class='type2'>$v[post_time]</li>";
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
            foreach ($time as $v) {
                if ($v['type'] == 1) {
                    echo "<li class='type1'>$v[diff]</li>";
                } else {
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
        <h3>坚持早睡 积分排行</h3>
        <div class="sign-body2">
            <?php
            foreach ($userRank as $v) {
                echo "<div class='user'>$v[id].$v[user] <span class='rank'> $v[rank]分</span> </div> ";
                echo "<hr/>";
            }
            ?>
        </div>
    </div>
</div>
<script src="js/sign-calendar.js"></script>

<script type="text/javascript" src="js/jquery-3.3.1.js" charset="utf-8"></script>
<script type="text/javascript" src="js/sign.js" charset="utf-8"></script>
<script>
    var uid = "<? echo $uid ?>";
    var username = "<? echo $username ?>";
    var going = "<? echo $going ?>";
    var signCalendar = new calendar();
    var d = signCalendar.getDay();
    var year = d.y;
    var month = d.m + 1;
    var today = d.d;
</script>
</body>
</html>