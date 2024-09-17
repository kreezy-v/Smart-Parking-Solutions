<?php
include 'db.php';
$brandIDAdd = 1;

if (isset($_POST['cBrand'])) {

    $mainId = $_POST['mainId'];
    $cBrand = $_POST['cBrand'];
    $cModel = $_POST['cModel'];
    $platNum = $_POST['pNumber'];

    $stmt = $conn->prepare("INSERT INTO `cardetails`(`accountsId`, `plateNumber`, `carBrandID`, `carModelID`) VALUES ('$mainId','$platNum','$cBrand','$cModel')");
    $stmt->execute();
}

if (isset($_POST['selectCar'])) {

    $mainCarNow = $_POST['selectCar'];
    $userId = $_POST['userId'];
    $stmt = $conn->prepare("UPDATE `accounts` SET `mainCar`='$mainCarNow' WHERE `id` = $userId");
    $stmt->execute();
}

if (isset($_POST['delCarID'])) {

    $delCarID = $_POST['delCarID'];

    $stmt = $conn->prepare("DELETE FROM `cardetails` WHERE `referenceId` = $delCarID");
    $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <!-- Or for RTL support -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<body style="background-color: azure; height: 90vh">
    <nav class="navbar fixed-top">
        <div class="container-fluid">
            <img src="csu-logo.png" alt="logocsu" width="50" />
            <a class="navbar-brand" href="cp.php"><b class="fs-1">SPS CSU CARIG</b></a>
            <button class="navbar-toggler border-0" type=" button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end bg-warning" tabindex="-1" id="offcanvasDarkNavbar"
                aria-labelledby="offcanvasDarkNavbarLabel">
                <div class="offcanvas-header">
                    <img src="csu-logo.png" alt="logocsu" width="50" />
                    <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel">
                        SPS CSU CARIG
                    </h5>
                    <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="cp.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="userscars.php">Cars</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="talkting.html">Chat Support</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="information.html">Info</a>
                        </li>
                        <div id="accessSide">
                            <li class="nav-item">
                                <button type="button" class="btn btn-danger w-100" onclick="outNa()">
                                    LOGOUT
                                </button>
                                <script>
                                    function outNa() {
                                        localStorage.removeItem("id");
                                        localStorage.removeItem("sID");
                                        localStorage.removeItem("pword");
                                        window.location.href = "cp.php";
                                    }
                                </script>
                            </li>
                        </div>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <script>
        if (localStorage.length == 0) {
            window.location.href = "cp.php";
        }
        $(document).ready(function () {
            $.ajax({
                url: 'carscheck.php',
                type: 'POST',
                data: { id: localStorage.getItem('id') },
                success: function (response) {
                    $("#getCars").html(response);
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });
    </script>
    <div class="container mt-5 pt-3" id="getCars" h-100>

    </div>
    <div class="modal fade" id="loginModal" data-bs-backdrop="static" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-warning">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">
                        <b>LOGIN</b>
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="cp.php" method="POST">
                        <div class="form-group my-1">
                            <label for="sID">School ID <sup>*</sup></label>
                            <input type="text" class="form-control" id="sID" name="sIDlog"
                                placeholder="School ID Example(20-20055)" />
                        </div>
                        <div class="form-group my-1">
                            <label for="password">Password <sup>*</sup></label>
                            <div class="col-12 d-flex">
                                <input type="password" class="form-control" id="password" name="pword"
                                    placeholder="Password" />
                                <button type="button" class="btn btn-light" onclick="seen()">
                                    <i class="bi bi-eye-fill" id="passIcon" style="color: green"></i>
                                </button>
                            </div>
                        </div>
                        <button class="btn btn-success btn-auth-submit my-1 w-100" type="submit">
                            Login
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>


        var accessSide = document.getElementById("accessSide");

        if (localStorage.length == 0) {
            accessSide.innerHTML =
                "<li class='nav-item'> <a type='button' class='btn btn-danger w-100' href='signup.php'>SIGN UP</a></li><br> <li class='nav-item'>  <button type='button' class='btn btn-success w-100' data-bs-toggle='modal' data-bs-target='#loginModal'>LOGIN</button></li>";
        }

    </script>
</body>

</html>