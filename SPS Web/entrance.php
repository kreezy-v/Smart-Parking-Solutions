<?php
include "db.php";
$stmt = $conn->prepare("SELECT servos FROM `slot`");
$stmt->execute();
$result = $stmt->fetchAll();
if ($result[0]['servos'][0] == '1') {
    echo '<img src="gateOpen.png" alt="yellowblock" width="75%" />';
} else {
    echo '<img src="gateClose.png" alt="yellowblock" width="75%" />';
}
?>