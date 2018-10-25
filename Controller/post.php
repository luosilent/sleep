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
            $sql2 = "update  member set rank=rank+1 WHERE (`username` = :username);";
            $stmt = $conn->prepare($sql2);
            $stmt->bindParam(":username", $_POST['username']);
             $stmt->execute();
        }
    }

    print_r($res);

}

