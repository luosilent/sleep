$(document).ready(function () {
    $.ajax({
        type: "post",
        url: "Controller/get.php",
        data: 'month=' + month + '&uid=' + uid,
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
                if (today == day && is_sign == 1) {
                    var btnSign = $("#btnSign");
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
        error: function () {
            console.log("gg");
        }
    });

    // 签到
    $('#btnSign').on('click', function () {
        var $btn = $(this);
        var is_sign = 1;
        var dd = new Date();
        var hours = dd.getHours();
        if (hours > 24 || hours < 18) {
            $btn.addClass('signing').attr('disabled', 'disabled').html("现在还不能签到...");
        }
        if ($btn.hasClass('signed') || $btn.hasClass('signing')) return;
        $.ajax({
            type: "POST",
            url: "Controller/post.php",
            data: 'is_sign=' + is_sign + '&year=' + year + '&month=' + month + '&day=' + today + '&uid=' + uid + '&username=' + username,
            success: function (res) {
                var gold = 1;
                console.log(res);
                if (going == 7) {
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
    $('#submit').on('click', function () {
        var $btn = $(this);
        var hour = $("#hour").val();
        var minute = $("#minute").val();
        var second = $("#second").val();
        var gold = 2;
        if (hour > 24 || hour < 18) {
            $('#warn').html("计划睡觉时间 只能在18-24小时之间");
            return
        }
        if (minute >= 60 || minute < 0) {
            $('#warn').html("分钟只能设置00-60之间");
            return
        }
        if (second >= 60 || second < 0) {
            $('#warn').html("秒 只能设置00-60之间");
            return
        }
        $.ajax({
            type: "POST",
            url: "Controller/post.php",
            data: 'gold=' + gold + '&username=' + username + '&hour=' + hour + '&minute=' + minute + '&second=' + second,
            success: function () {
                $btn.val("修改成功");
                setTimeout(window.location.reload.bind(window.location), 1000);
            }

        })
    });

    $("#close").on('click', function () {
        if (confirm("您确定要关闭本页吗？")) {
            $.ajax({
                url: "Controller/logout.php",
                type: "post",
                data: {"uid": uid},
                success: function (res) {
                    var obj = JSON.parse(res);
                    if (obj.code == 0) {
                        window.location.href=("login.php");
                    } else if (obj.code == 1) {
                        alert(obj.msg);
                    }
                }
            });
        }
    });
})