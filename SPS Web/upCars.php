<?php
require "db.php";

if (!empty($_POST)) {
    $cars = $_POST['cars'];

    $stmt = $conn->prepare("UPDATE `slot` SET `cars`= '$cars' WHERE id=0");
    $stmt->execute();

    $stmt = $conn->prepare("UPDATE `slot` SET `slotNumber`= $cars[0] WHERE id=0");
    $stmt->execute();
}
?>