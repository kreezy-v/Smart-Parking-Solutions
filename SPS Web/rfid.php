<?php
require "db.php";

if (!empty($_POST)) {
    $rfidTmp = trim($_POST['rfidText']);

    $stmt = $conn->prepare("SELECT * FROM `accounts` WHERE `rfidText` = '$rfidTmp'");
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $result = $stmt->fetchAll();
        $id = $result[0]['id'];
        $name = $result[0]['fullName'];
        $name = explode(" ", $name);
        echo "#" . $id . $name[0];
    } else {
        echo " ";
    }
    $stmt = $conn->prepare("UPDATE `slot` SET `servos`='10' WHERE 1");
    $stmt->execute();
}
?>