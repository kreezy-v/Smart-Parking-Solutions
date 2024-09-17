<?php
require "db.php";

if (!empty($_POST)) {
    $slot = $_POST['slot'];

    date_default_timezone_set('Asia/Manila');
    $newTime = date('h:i A');
    $newDate = date('Y-m-d');
    $stmt = $conn->prepare("SELECT `id` FROM  `datefilter` WHERE `parkedSlot` = $slot AND `recentDate` = '$newDate'");
    $stmt->execute();
    $result = $stmt->fetchAll();
    if ($stmt->rowCount() > 0) {
        $stmt = $conn->prepare("UPDATE `datefilter` SET `tOut`= '$newTime' WHERE `parkedSlot`=$slot AND `recentDate` = '$newDate'");
    }
    $stmt->execute();
    $stmt = $conn->prepare("UPDATE `accounts` SET `slot`= 0 WHERE `slot`=$slot");
    $stmt->execute();

    $stmt = $conn->prepare("UPDATE `slot` SET `servos`='01' WHERE 1");
    $stmt->execute();
}
?>