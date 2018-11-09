<?php
require '../Connect/conn.php';
$conn = conn();
if ($_POST['gold']){
    $gold = $_POST['gold'];
}else{
    $gold = null;
}
if ($gold == 1) {
    $sql = "update `member` set `rank`=rank+5 WHERE (`username` = :username)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":username", $_POST['username']);
    $stmt->execute();
}
if ($gold == 2) {
    $hour = $_POST['hour'];
    $minute = $_POST['minute'];
    $second = $_POST['second'];
    $time = strtotime("$hour:$minute:$second");
    $sleep_time = date("H:i:s", $time);
    $sql = "update `member` set `sleep_time`= :sleep_time WHERE (`username` = :username)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":username", $_POST['username']);
    $stmt->bindParam(":sleep_time", $sleep_time);
    $res = $stmt->execute();
} else {
    $sql = "SELECT * FROM  `sign` WHERE  `uid` = :uid ORDER BY id DESC LIMIT 0,1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":uid", $_POST['uid']);
    $re = $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $day = $row['day'];
    }
    if ($day == $_POST['day']){
        $msg['code'] = 404;
        $msg['exp'] = "gg";
       print json_encode($msg);
    }else {
        $sql = "INSERT INTO sign (`uid`,`is_sign`,`year`,`month`,`day`) 
VALUES (:uid,:is_sign,:year,:month,:day)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":uid", $_POST['uid']);
        $stmt->bindParam(":is_sign", $_POST['is_sign']);
        $stmt->bindParam(":year", $_POST['year']);
        $stmt->bindParam(":month", $_POST['month']);
        $stmt->bindParam(":day", $_POST['day']);

        $res = $stmt->execute();
        if ($res) {
            $sql2 = "update member set rank=rank+1 WHERE (`username` = :username)";
            $stmt = $conn->prepare($sql2);
            $stmt->bindParam(":username", $_POST['username']);
            $stmt->execute();
            $sql3 = "SELECT * FROM  `sign` WHERE  `uid` = :uid ORDER BY id DESC LIMIT 1,1";
            $stmt1 = $conn->prepare($sql3);
            $stmt1->bindParam(":uid", $_POST['uid']);
            $re = $stmt1->execute();
            if ($re) {
                while ($row = $stmt1->fetch(PDO::FETCH_ASSOC)) {
                    $going = $row['going'];
                    $old_time = $row['post_time'];
                }
                $date = strtotime($old_time);
                $old_time = date("Y-m-d", $date);
                $new_time = date("Y-m-d");
                $time_diff = strtotime($new_time) - strtotime($old_time);
                $diff = intval($time_diff / 86400);
                if ($going >= 7 || $diff > 1) {
                    $going = 0;
                }
                $sql4 = "update `sign` set `going` = $going+1 WHERE (`uid` = :uid) ORDER by id DESC LIMIT 1";

                $stmt = $conn->prepare($sql4);
                $stmt->bindParam(":uid", $_POST['uid']);
                $stmt->execute();

            }
        }
    }
}

