<?php
include "db.php";
$stmt = $conn->prepare("UPDATE `slot` SET `servos`='00' WHERE 1");
$stmt->execute();
?>