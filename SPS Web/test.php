<?php
include "db.php";

$locked = "no";
$tIn = "";
$tOut = "";
if (isset($_POST)) {
    $lockID = $_POST['id'];
    date_default_timezone_set('Asia/Manila');
    $newDate = date('Y-m-d');
    $stmt = $conn->prepare("SELECT * FROM `datefilter` WHERE `id` = $lockID AND `recentDate` = '$newDate'");
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $result = $stmt->fetchAll();
        $tIn = $result[0]['tIn'];
        $tOut = $result[0]['tOut'];
    }
    $stmt = $conn->prepare("SELECT * FROM `accounts` WHERE id = $lockID");
    $stmt->execute();
    $result = $stmt->fetchAll();
    if ($result[0]['slot'] == 0) {
        $locked = "no";
    } else {
        $locked = "yes";
    }
}

if ($locked == "no") {
    $stmt = $conn->prepare("SELECT slotNumber FROM `slot` WHERE id = 0");
    $stmt->execute();
    $result = $stmt->fetchAll();
}

?>
<div class="row bg-dark text-white mx-0">
    <div class="col text-center h4 mb-0">Last Time In</div>
    <div class="col text-center h4 mb-0">Last Time Out</div>
</div>
<div class="row bg-dark text-white mx-0">
    <div class="col text-center h3 mb-0" id="updateTin"><?php echo $tIn ?></div>
    <div class="col text-center h3 mb-0" id="updateTout"><?php echo $tOut ?></div>
</div>

<div class="row mx-0">
    <div class="col text-center h1 mb-0" id="updateText">
        <b>
            <?php if ($locked == "yes") {
                echo '<i class="bi bi-car-front-fill"></i> PARKED';
            } else {
                echo 'Nearest Slot';
            } ?>
        </b>
    </div>
</div>
<div class="title-wrapper mt-5">
    <h1 class="sweet-title mt-3 d-flex <?php if ($locked == "yes") {
        echo "text-dark";
    } ?>">
        <span data-text="<?php

        if ($locked == "no") {

            if ($result[0]['slotNumber'] == 0) {
                echo "FULL";
            } else {

                echo $result[0]['slotNumber'];
            }
        } else {
            echo $result[0]['slot'];
        }
        ?>">
            <?php
            if ($locked == "no") {
                if ($result[0]['slotNumber'] == 0) {
                    echo "FULL";
                } else {

                    echo $result[0]['slotNumber'];
                }
            } else {
                echo $result[0]['slot'];
            }
            ?>
        </span>
    </h1>
</div>