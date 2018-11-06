<?php
define('ROOT_PATH', dirname(dirname(__FILE__)));
require ROOT_PATH.'./Connect/conn.php';
//set_time_limit(0);

if (isset($_POST['month'])) {
    $month = $_POST['month'];
    $uid = $_POST['uid'];
    $data = array();
    $conn = conn();
    $sql = "SELECT * FROM sign WHERE (`month`= :mon) AND (`uid`= :uid)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":mon", $month);
    $stmt->bindParam(":uid", $uid);
    $res = $stmt->execute();
    if ($res) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = array(
                'uid' => $row['uid'],
                'is_sign' => $row['is_sign'],
                'year' => $row['year'],
                'month' => $row['month'],
                'day' => $row['day'],
                'post_time' => $row['post_time'],
            );
        }
    } else {
        $data[] = array();
    }

    print_r(json_encode($data));

}

function getUser($name)
{
    $conn = conn();
    $user = array();

    $sql = "select * from member where username = :username";
    $stmt = $conn->prepare($sql);

    $stmt->bindParam(":username", $name);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $user = $row;
    }

    return $user;
}

function getSign($uid)
{
    $conn = conn();
    $sql = "select count(is_sign) from sign where uid = :uid AND is_sign = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":uid", $uid);
    $rv = $stmt->execute();
    if ($rv) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $sign = $row['count(is_sign)'];
        }
    }
    return $sign;
}

function getGoing($uid){
    $conn = conn();
    $sql = "SELECT * FROM  `sign` WHERE  `uid` = :uid AND is_sign = 1 ORDER BY id DESC LIMIT 0,1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":uid", $uid);
    $re = $stmt->execute();
    if ($re) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $going = $row['going'];
            $old_time = $row['post_time'];
        }
        $date = strtotime($old_time);
        $old_time = date("Y-m-d",$date);
        $new_time = date("Y-m-d");
        $time_diff = strtotime($new_time) - strtotime($old_time);
        $diff = intval($time_diff / 86400);

        if ($diff > 1)
            $going = 0;
    }
    return $going;
}

function getTime($uid){
    $conn = conn();
    $sql = "SELECT `post_time` FROM  `sign` WHERE  `uid` = :uid AND is_sign = 1 ORDER BY post_time DESC LIMIT 0,10";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":uid", $uid);
    $re = $stmt->execute();
    if ($re) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $res[] = strtotime($row['post_time']) ;
        }
    }
    foreach ($res as $k => $v) {

        $post_time =  $v;
        $new_time = strtotime(date("Y-m-d 23:00:00"));
        if ($post_time < $new_time) {
            $flag = "早睡了";
            $to = "继续保持";
            $type = 1;
        } else {
            $flag = "晚睡了";
            $to = "注意身体";
            $times = $post_time;
            $post_time = $new_time;
            $new_time = $times;
            $type = 2;
        }
        $hour = floor(($new_time - $post_time) % 86400 / 3600);
        $minute = floor(($new_time - $post_time) % 86400 % 3600 / 60);
        $second = floor(($new_time - $post_time) % 86400 % 3600 % 60);
        if ($hour < 10){
            $hour = "0".$hour;
        }
        if ($minute < 10){
            $minute = "0".$minute;
        }
        if ($second < 10){
            $second = "0".$second;
        }

        $r[$k]['diff'] = $flag . $hour . "小时" . $minute . "分" . $second . "秒".$to;
        $r[$k]['post_time'] = date("Y-m-d H:i:s",$v);
        $r[$k]['type'] = $type;
    }

    return $r;
}

function getRank()
{
    $conn = conn();


    $sql = "select * from member ORDER BY rank LIMIT 0,10";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $res[]= $row['username'];
    }
    foreach ($res  as $k => $v){
        $r[$k]['user'] = $v;
        $count= $k + 1;
        $r[$k]['id'] = $count;
    }

    return $r;
}