<?php
/**
 * Created by PhpStorm.
 * User: luosilent
 * Date: 2018/9/20
 * Time: 16:53
 */
error_reporting(0);
function conn()
{
    $charset = 'utf8';
    $dsn = 'mysql:host=localhost;dbname=sign';
    $uName = "root";
    $pWord = "root";

    try {
        $conn = new PDO($dsn, $uName, $pWord, array(PDO::ATTR_PERSISTENT => true));
        $conn->query("set NAMES $charset");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo iconv('gbk', 'utf-8', $e->getMessage());
        die();
    }

    return $conn;

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
    $res = array();

    $sql = "select count(is_sign) from sign where uid = :uid AND is_sign = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":uid", $uid);
    $rv = $stmt->execute();
    if ($rv) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $sign = $row['count(is_sign)'];
        }
    }

    $sql2 = "select * from sign WHERE (`uid` = :uid) order by id DESC limit 1;";
    $stmt = $conn->prepare($sql2);
    $stmt->bindParam(":uid", $uid);
    $re = $stmt->execute();
    if ($re) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $going = $row['going'];
            $old_time = date("Y-m-d",$row['post_time']);
        }

        $new_time = date("Y-m-d");
        $time_diff = strtotime($new_time) - strtotime($old_time);
        $diff = intval($time_diff / 86400);
        if ($diff > 1)
            $going = 0;
        $res['sign'] = $sign;
        $res['going'] = $going;
    }
    return $res;
}

