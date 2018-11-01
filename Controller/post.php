<?php
require '../Connect/conn.php';
session_start();
if (isset($_POST['is_sign'])) {
    $conn = conn();
    $sql = "INSERT INTO sign (`uid`,`is_sign`,`year`,`month`,`day`) 
VALUES (:uid,:is_sign,:year,:month,:day)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":uid", $_POST['uid']);
    $stmt->bindParam(":is_sign", $_POST['is_sign']);
    $stmt->bindParam(":year", $_POST['year']);
    $stmt->bindParam(":month", $_POST['month']);
    $stmt->bindParam(":day", $_POST['day']);

    $res = $stmt->execute();
    if ($res){
        if (isset( $_POST['username'])) {
            $sql2 = "update member set rank=rank+1 WHERE (`username` = :username);";
            $stmt = $conn->prepare($sql2);
            $stmt->bindParam(":username", $_POST['username']);
             $stmt->execute();
            $sql3 = "select * from sign  WHERE (`uid` = :uid) ORDER by id DESC limit 2;";
            $stmt = $conn->prepare($sql3);
            $stmt->bindParam(":uid",$_POST['uid']);
            $re = $stmt->execute();
            if ($re) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $going = $row['going'];
                    $old_time = date("Y-m-d",$row['post_time']);
                }
                $new_time = date("Y-m-d");
                $time_diff =strtotime($new_time)  - strtotime($old_time);
                $diff = intval( $time_diff / 86400 );
                if ($going >= 7 || $diff > 1) {
                    $going = 0;
                }
                $sql4 = "update sign set going=$going+1 WHERE (`uid` = :uid) ORDER by id DESC limit 1;";
                $stmt = $conn->prepare($sql4);
                $stmt->bindParam(":uid", $_POST['uid']);
                $stmt->execute();

            }
        }
    }

    print_r($res);

}


