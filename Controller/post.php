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

    print_r($res);

}

