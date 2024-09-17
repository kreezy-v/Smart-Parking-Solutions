<?php
require "db.php";

if (!empty($_POST)) {
    $id = $_POST['id'];
    $slotNumTmp = $_POST['slotNum'];

    date_default_timezone_set('Asia/Manila');
    $newTime = date('h:i A');
    $newDate = date('Y-m-d');
    $stmt = $conn->prepare("SELECT `id` FROM  `datefilter` WHERE `id` = $id AND `recentDate` = '$newDate'");
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $stmt = $conn->prepare("UPDATE `datefilter` SET `parkedSlot`= $slotNumTmp ,`tIn`= '$newTime' WHERE `id` = $id AND `recentDate` = '$newDate'");
    } else {
        $stmt = $conn->prepare("INSERT INTO `datefilter`(`id`, `recentDate`, `tIn`, `parkedSlot`) VALUES ('$id','$newDate','$newTime','$slotNumTmp')");
    }
    $stmt->execute();
    $stmt = $conn->prepare("UPDATE `accounts` SET `slot`= $slotNumTmp WHERE id=$id");
    $stmt->execute();
}
?>