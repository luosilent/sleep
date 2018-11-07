<?php
/**
 * Created by PhpStorm.
 * User: luosilent
 * Date: 2018/9/20
 * Time: 16:53
 */
//error_reporting(0);
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