<?php

require '../Connect/conn.php';
session_start();
if (isset($_POST['content'])) {
    $content = htmlspecialchars($_POST['content']);
    $conn = conn();
    $sql = "INSERT INTO talkroom (`content`,`uid`) VALUES (:content,:uid)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":content", $content);
    $stmt->bindParam(":uid", $_SESSION['uid']);
    $res = $stmt->execute();

    return $res;

}

