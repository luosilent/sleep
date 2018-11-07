/**
 * 日历
 */
function calendar() {
    // 12个月的天数
    var moday = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    var d = new Date();
    var years = d.getFullYear(); //当前的年份
    var month = d.getMonth(); //当前的月份
    var days = d.getDate(); //当前月的号数
    var grid = 7 * 6; //日历格子
    var opt = {
        id: '',
        signDays: [12, 3, 8],
        week: ['日', '一', '二', '三', '四', '五', '六'],
        going: 1
    };

    var momatch = [0, 1, 2, 3, 4, 5, 6];//这个存的是当前月的一号前面有多少空位，周日没有，周一一个以此类推
    var lastMonthDays = momatch[d.getDay()];

    /**
     * 闰年设置
     * @param  {d} 日期
     * @return {[boole]}
     */
    var leapYear = function (d) {
        var leap = years % 4;

        if (leap != 0) {
            moday[1] = 28;
            return true;
        } else {
            moday[1] = 29;
            return false;
        }
    };
    /**
     * 设置参数
     */
    var setOptions = function (options) {
        for (var key in options) {
            if (!opt[key]) {
                opt[key] = options[key];
            } else if (opt[key].toString() !== options[key].toString()) {
                opt[key] = options[key];
            }
        }
    };
    /**
     * [daysViews 一个月的html]
     * @return {[type]} [description]
     */
    var daysViews = function () {
        d.setDate(1);
        var oneWeek = d.getDay(); //本月1号星期几
        var curDays = moday[month];//本月有几天
        var lastGrid = momatch[oneWeek];//上个月占几格
        var lastDays = moday[month - 1 < 0 ? 11 : month - 1];//上个月有几天
        var v = '<ul class="days">';
        // 上个月数据
        for (var i = lastGrid; i > 0; i--) {
            var day = lastDays - i + 1;
            v += '<li id="last_day_' + i + '" class="last" data-day="' + day + '">' + day + '</li>';
            grid--;
        }
        // 本月数据
        for (var i = 1; i <= curDays; i++) {
            var is_sign = in_array(i, opt.signDays);//是否签到
            v += '<li id="day_' + i + '" class="day ' + (days == i ? 'today' : '') + (is_sign ? ' signed' : '') + '" >' + i + '</li>';
            grid--;
        }
        //下个月
        for (var i = 1; i <= grid; i++) {
            v += '<li id="next_day_' + i + '" class="next" >' + i + '</li>';
        }
        v += '</ul>'
        return v;
    };
    /**
     * [weekViews 一周的html]
     * @return {[html]}
     */
    var weekViews = function () {
        var v = '<ul class="week">';
        var week = opt.week;
        for (var i = 0; i < week.length; i++) {
            v += '<li id="week_' + i + '">' + week[i] + '</li>';
        }
        v += '</ul>';
        return v;
    };
    /**
     * [barAnimate 签到进度条动画]
     * @param  {[Number]} days [天数]
     */
    var barAnimate = function () {
        var going = opt.going;
        var $curNum = document.getElementById('signNum' + going);
        var $barLoading = document.getElementById('signBarLoading');
        var left = $curNum.offsetLeft;
        var width = $curNum.offsetWidth;

        $barLoading.style.width = left + width / 2 + 'px';
        for (var i = 1; i < going; i++) {
            var $nextNum = document.getElementById('signNum' + i);
            $nextNum.className = $nextNum.className.replace('current', '') + ' active';
        }
        $curNum.className = $curNum.className + ' current';
    };
    var dayAnimate = function (day) {
        var day = day || days;
        var $day = document.getElementById('day_' + day);
        $day.className = $day.className + ' sign_ok';
    };
    /**
     * [in_array search 是否在 array 中]
     * @param  {[String]} search [查询对象]
     * @param  {[Array]} array  [查询数组]
     * @return {[boole]}        [返回值]
     */
    var in_array = function (search, array) {
        for (var i in array) {
            if (array[i] == search) {
                return true;
            }
        }
        return false;
    };
    /**
     * 构造函数
     * @param {options} 外部配置参数
     */
    Calendar = {
        init: function (options) {
            if (typeof options == 'object') {
                setOptions(options);
            }
            // 设置闰年
            var is_leap = leapYear();
            // 日历容器对象
            var $obj = document.getElementById(opt.id);

            var html = '<div class="sign-calendar-head">';
            html += '<div class="years">' + years + '年' + (month + 1) + '月</div>';
            html += weekViews();
            html += '</div>';
            html += daysViews();
            $obj.innerHTML = html;
            // 数组持续签到状态
            barAnimate(opt.going);
        },
        getDay: function () {
            return {
                y: years,
                m: month,
                d: days
            };
        },
        play: function (day) {
            opt.going = opt.going + 1;
            barAnimate();
            dayAnimate(day);
        }

    };

    return Calendar;

}
