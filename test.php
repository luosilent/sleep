<?php
/**
 * Created by PhpStorm.
 * User: luosilent
 * Date: 2018/11/1
 * Time: 15:35
 */
require 'Connect/conn.php';

$conn = conn();
date_default_timezone_set("Asia/Shanghai");
function diff($begin_time, $end_time)
{
    if ( $begin_time < $end_time ) {
        $s_time = $begin_time;
        $e_time = $end_time;
    } else {
        $s_time = $end_time;
        $e_time = $begin_time;
    }
    $time_diff =strtotime($e_time)  - strtotime($s_time);
    $days = intval( $time_diff / 86400 );

    return $days;
}
//$new_time = date("Y-m-d h:m:s");
//echo diff("2018-10-31 16:00:10",$new_time);

//$hour = 23;
//$minute = 22;
//$second = 22;
//$time = strtotime("$hour:$minute:$second");
//$sleep_time = date("H:i:s",$time);
//print_r($sleep_time);

function getTime1($uid){
    $conn = conn();
    $sql = "SELECT `post_time` FROM  `sign` WHERE  `uid` = :uid AND is_sign = 1 ORDER BY id DESC LIMIT 0,30";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":uid", $uid);
    $re = $stmt->execute();
    if ($re) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $res[] = strtotime($row['post_time']) ;
        }
    }
        foreach ($res as $k => $v) {

            $post_time = $v;
            $new_time = strtotime(date("Y-m-d 23:00:00"));
            if ($post_time < $new_time) {
                $flag = "早睡了";
            } else {
                $flag = "晚睡了";
                $times = $post_time;
                $post_time = $new_time;
                $new_time = $times;
            }
            $hour = floor(($new_time - $post_time) % 86400 / 3600);
            $minute = floor(($new_time - $post_time) % 86400 % 3600 / 60);
            $second = floor(($new_time - $post_time) % 86400 % 3600 % 60);

            $r[$k]['diff'] = $flag . $hour . "小时" . $minute . "分" . $second . "秒";
            $r[$k]['post_time'] = date("Y-m-d H:i:s",$v);
        }

    return $r;
}
$res = getTime1(1);
print_r($res);