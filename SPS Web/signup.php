<?php

include 'db.php';

$brandID = 1;
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

<body style="background-color: azure">
    <nav class="navbar fixed-top">
        <div class="container-fluid">

            <img src="csu-logo.png" alt="logocsu" width="50">
            <a class="navbar-brand" href="cp.php"><b class="fs-1">SPS CSU CARIG</b></a>
            <button class="navbar-toggler border-0" type=" button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end bg-warning" tabindex="-1" id="offcanvasDarkNavbar"
                aria-labelledby="offcanvasDarkNavbarLabel">
                <div class="offcanvas-header">
                    <img src="csu-logo.png" alt="logocsu" width="50">
                    <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel">SPS CSU CARIG</h5>
                    <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="cp.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="talkting.html">Chat Support</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="information.html">Info</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <div class="container mt-5 pt-3">

        <div class="row align-items-center my-t py-5 px-0">
            <div class="col-md-6 mb-4 mb-md-0 p-1 ">
                <div class="mx-auto bg-warning rounded p-2">
                    <h2 class="auth-section-title"><b>Register account</b></h2>
                    <p class="auth-section-subtitle"><i>Register your account to continue.</i></p>
                    <form action="cp.php" method="POST">
                        <div class="form-group my-1">
                            <label for="rfid">RFID <sup>*</sup></label>
                            <div class="col-12 d-flex">
                                <?php

                                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                    $value = $_POST['rfid'];
                                } else {
                                    $value = "";
                                }
                                ?>
                                <input type="text" class="form-control" id="rfid" name="rfid" placeholder="RFID"
                                    value="<?php echo $value ?>" maxlength="11">
                                <a type="button" href="scan.html" class="btn btn-secondary">
                                    <i class="bi bi-qr-code-scan"></i>
                                </a>
                            </div>


                        </div>
                        <div class="form-group my-1">
                            <label for="fName">Receipt No. <sup>*</sup></label>
                            <input type="text" class="form-control" id="orNum" name="orNum" placeholder="Receipt No.">
                        </div>
                        <div class="form-group my-1">
                            <label for="fName">First Name <sup>*</sup></label>
                            <input type="text" class="form-control" id="fName" name="fName" placeholder="First Name">
                        </div>
                        <div class="form-group my-1">
                            <label for="lName">Last Name <sup>*</sup></label>
                            <input type="text" class="form-control" id="lName" name="lName" placeholder="Last Name">
                        </div>
                        <div class="form-group my-1">
                            <label for="sID">School ID <sup>*</sup></label>
                            <input type="text" class="form-control" id="sID" name="sID"
                                placeholder="School ID Example(20-20055)">
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
                        <div class="form-group my-1">
                            <label for="lName">Car Brand <sup>*</sup></label>
                            <select class="form-select select-brand" id="cBrand" name="cBrand">
                                <?php
                                $stmt = $conn->prepare("SELECT * FROM `carbrands`");
                                $stmt->execute();
                                $resultBrands = $stmt->fetchAll();

                                foreach ($resultBrands as $row) {
                                    echo "<option value='" . $row['id'] . "'>" . $row['brandName'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <script>
                            $('#cBrand').on('change', function () {
                                var loadVal = $("#cBrand").val();
                                $("#newModels").load("carmodels.php", { newID: loadVal });
                            });
                        </script>
                        <div class="form-group my-1" id="newModels">
                            <label for="lName">Car Model <sup>*</sup></label>
                            <select class="form-select select-model" id="cModel" name="cModel">
                                <?php
                                $stmt = $conn->prepare("SELECT * FROM `carmodels` WHERE `brandID` = $brandID");
                                $stmt->execute();
                                $resultModels = $stmt->fetchAll();

                                foreach ($resultModels as $row) {
                                    echo "<option value='" . $row['id'] . "'>" . $row['modelName'] . "</option>";
                                }
                                ?>
                            </select>
                            <script>
                                $(document).ready(function () {
                                    {
                                        $('.select-model').select2({
                                            theme: 'bootstrap-5'
                                        });
                                    }
                                });
                            </script>
                        </div>
                        <div class="form-group my-1">
                            <label for="lName">Plate Number <sup>*</sup></label>
                            <input type="text" class="form-control" id="pNumber" name="pNumber"
                                placeholder="Plate Number">
                        </div>
                        <button class="btn btn-danger btn-auth-submit my-1 w-100" type="submit">Register</button>
                    </form>
                    <p class="mb-0 text-center">
                        <a href="cp.php" class="text-dark my-1 text-decoration-none">Already have an acocunt? LOGIN</a>
                    </p>
                </div>
            </div>

        </div>
    </div>
    <script>
        $(document).ready(function () {
            {
                $('.select-brand').select2({
                    theme: 'bootstrap-5'
                });
            }
        });
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