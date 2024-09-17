<?php

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['uID'])) {
        $uID = $_POST['uID'];
        $pWord = $_POST['pword'];
        $stmt = $conn->prepare("SELECT * FROM `accounts` WHERE `sID` = '$uID' AND `pword` = '$pWord'");
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll();
            $localStore = $result[0]['id'];
            echo "<script>localStorage.setItem('id', '" . $result[0]['id'] . "'); localStorage.setItem('uID', '" . $result[0]['sID'] . "'); localStorage.setItem('pword', '" . $result[0]['pword'] . "'); localStorage.setItem('accType', '" . $result[0]['accType'] . "');</script>";
            echo "<script>window.location.href = 'index.html';</script>";
        }

    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body style="background-color: azure">
    <nav class="navbar fixed-top">
        <div class="container-fluid">
            <img src="csu-logo.png" alt="logocsu" width="25" />
            <a class="navbar-brand" href="index.html"><b class="fs-5">SPS CSU CARIG - ADMIN</b></a>
            <button class="navbar-toggler border-0" type=" button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar">
            </button>
        </div>
    </nav>
    <div class="container mt-5 pt-3">

        <div class="row align-items-center my-t py-5 px-0">
            <div class="col-md-6 mb-4 mb-md-0 p-1 ">
                <div class="mx-auto bg-warning rounded p-2">
                    <h2 class="auth-section-title"><b>ADMIN LOGIN</b></h2>
                    <p class="auth-section-subtitle"><i>Login to continue.</i></p>
                    <form action="login.php" method="POST">
                        <div class="form-group my-1">
                            <label for="uID">Username <sup>*</sup></label>
                            <input type="text" class="form-control" id="uID" name="uID" placeholder="Username">
                        </div>
                        <div class="form-group my-1">
                            <label for="password">Password <sup>*</sup></label>
                            <div class="col-12 d-flex">
                                <input type="password" class="form-control" id="password" name="pword"
                                    placeholder="Password">
                                <button type="button" class="btn btn-light" onclick="seen()">
                                    <i class="bi bi-eye-fill" id="passIcon" style="color:green"></i>
                                </button>
                            </div>

                        </div>
                        <button class="btn btn-success btn-auth-submit my-1 w-100" type="submit">Login</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <script>
        var passIcon = $("#passIcon");
        function seen() {
            if (passIcon.hasClass("bi bi-eye-fill")) {
                passIcon.removeClass("bi bi-eye-fill");
                passIcon.addClass("bi bi-eye-slash-fill");
                passIcon.css("color", "red")
                $("#password").prop("type", "text");
            }
            else if (passIcon.hasClass("bi bi-eye-slash-fill")) {
                passIcon.removeClass("bi bi-eye-slash-fill");
                passIcon.addClass("bi bi-eye-fill");
                passIcon.css("color", "green")
                $("#password").prop("type", "password");
            }
        }
    </script>

</body>

</html>