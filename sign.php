<?php

if (isset($_COOKIE['username'])) {
   $username = $_COOKIE['username'];
}else{
    header("Location: index.php");
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
				<a class="avatar " href="#" title="huihong"><i class="fa fa-user fa-4x"></i></a>
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
		$(function(){

			// New Calendar
			var signCalendar = new calendar();

			var opt = {
				id:'calendarViews',//日历
				signDays:[1],//已经签到的日期[1,2,3]
				going:2//签到持续天数<=7
			};
			// 初始化日历
			signCalendar.init(opt);

			// 签到
			$(document).on('click','#btnSign',function(){
				var $btn = $(this);
				if($btn.hasClass('signed')||$btn.hasClass('signing')) return;
				$btn.addClass('signing').attr('disabled','disabled').html("正在签到...");

				// 延时两秒看效果
				setTimeout(function(){
					// 获取年月日 方法: signCalendar.getDay() ;
					var d = signCalendar.getDay();
					// 签到成功效果
					$btn.removeClass('signing').addClass('signed').html("已签到");
					signCalendar.play(d.d);
				},2000);
			})
		});
	</script>
</body>
</html>