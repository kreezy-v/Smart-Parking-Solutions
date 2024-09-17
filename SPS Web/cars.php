<?php
include "db.php";

$stmt = $conn->prepare("SELECT cars FROM `slot` WHERE id = 0");
$stmt->execute();
$result = $stmt->fetchAll();

date_default_timezone_set('Asia/Manila');
$newDate = date('Y-m-d');
$stmt = $conn->prepare("SELECT * FROM  `datefilter` WHERE `recentDate` = '$newDate'");
$stmt->execute();
$resultDate = $stmt->fetchAll();
$stmt = $conn->prepare("SELECT slot,fullName FROM `accounts`");
$stmt->execute();
$resultAcc = $stmt->fetchAll();

$slot1 = "";
$slot1Time = "";
$slot2 = "";
$slot2Time = "";
$slot3 = "";
$slot3Time = "";
foreach ($resultDate as $row) {
    foreach ($resultAcc as $row2) {
        if ($row['parkedSlot'] == $row2['slot']) {
            if ($row2['slot'] == '1') {
                $stmt = $conn->prepare("SELECT `fullName` FROM `accounts` WHERE `slot` = 1");
                $stmt->execute();
                $resultSlot = $stmt->fetchAll();
                $slot1 = $resultSlot[0]['fullName'];
                $stmt = $conn->prepare("SELECT `tIn` FROM `datefilter` WHERE `parkedSlot` = 1 AND `recentDate` = '$newDate'");
                $stmt->execute();
                $resultSlot = $stmt->fetchAll();
                $slot1Time = $resultSlot[0]['tIn'];
            } else if ($row2['slot'] == '2') {
                $stmt = $conn->prepare("SELECT `fullName` FROM `accounts` WHERE `slot` = 2");
                $stmt->execute();
                $resultSlot = $stmt->fetchAll();
                $slot2 = $resultSlot[0]['fullName'];
                $stmt = $conn->prepare("SELECT `tIn` FROM `datefilter` WHERE `parkedSlot` = 2 AND `recentDate` = '$newDate'");
                $stmt->execute();
                $resultSlot = $stmt->fetchAll();
                $slot2Time = $resultSlot[0]['tIn'];
            } else if ($row2['slot'] == '3') {
                $stmt = $conn->prepare("SELECT `fullName` FROM `accounts` WHERE `slot` = 3");
                $stmt->execute();
                $resultSlot = $stmt->fetchAll();
                $slot3 = $resultSlot[0]['fullName'];
                $stmt = $conn->prepare("SELECT `tIn` FROM `datefilter` WHERE `parkedSlot` = 3 AND `recentDate` = '$newDate'");
                $stmt->execute();
                $resultSlot = $stmt->fetchAll();
                $slot3Time = $resultSlot[0]['tIn'];
            }
            $updatedDate = true;
        }
    }
}
// foreach ($slots as $row) {
//     if ($row['slot'] == '1') {
//         $slot1 = $row['fullName'];
//         $slot1Time = $row['tIn'];
//     } else if ($row['slot'] == '2') {
//         $slot2 = $row['fullName'];
//         $slot2Time = $row['tIn'];
//     } else if ($row['slot'] == '3') {
//         $slot3 = $row['fullName'];
//         $slot3Time = $row['tIn'];
//     }
// }


?>
<div class="col-3 bg-dark m-1 p-2" style="width: 20%">
    <div class="bg-<?php if ($result[0]['cars'][1] == 0) {
        echo "success";
    } else {
        echo "danger";
    } ?> h-100 text-white text-center">
        <img src="block.png" alt="yellowblock" width="100%" />
        <span style="font-size: 8rem">1</span>
    </div>
</div>
<div class="col-3 bg-dark m-1 p-2" style="width: 20%">
    <div class="bg-<?php if ($result[0]['cars'][2] == 0) {
        echo "success";
    } else {
        echo "danger";
    } ?> h-100 text-white text-center">
        <img src="block.png" alt="yellowblock" width="100%" />
        <span style="font-size: 8rem">2</span>
    </div>
</div>
<div class="col-3 bg-dark m-1 p-2" style="width: 20%">
    <div class="bg-<?php if ($result[0]['cars'][3] == 0) {
        echo "success";
    } else {
        echo "danger";
    } ?> h-100 text-white text-center">
        <img src="block.png" alt="yellowblock" width="100%" />
        <span style="font-size: 8rem">3</span>
    </div>
</div>
<div class="col-3 bg-dark m-1 p-2" style="width: 20%">
    <div class="bg-danger h-100 text-white text-center">
        <img src="block.png" alt="yellowblock" width="100%" />
        <span style="font-size: 8rem">4</span>
    </div>
</div>
<div class="row text-center" style="width: 84%;">
    <div class="col-3">
        <span><?php echo $slot1 ?></span>
        <br>
        <span><?php echo $slot1Time ?></span>
    </div>
    <div class="col-3">
        <span><?php echo $slot2 ?></span>
        <br>
        <span><?php echo $slot2Time ?></span>
    </div>
    <div class="col-3">
        <span><?php echo $slot3 ?></span>
        <br>
        <span><?php echo $slot3Time ?></span>
    </div>
    <div class="col-3">
        <span>UNDER CONSTRUCTION</span>
    </div>
</div>