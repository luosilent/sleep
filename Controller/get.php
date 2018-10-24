<?php
require '../Connect/conn.php';
//set_time_limit(0);
session_start();
$month = $_POST['month'];
if (isset($month)) {
    $conn = conn();
    $sql = "SELECT * FROM sign WHERE (`month`= :mon) ";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":mon", $month);
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