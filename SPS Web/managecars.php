<?php
include "db.php";

$stmt = $conn->prepare("SELECT * FROM `carbrands`");
$stmt->execute();
$resultBrands = $stmt->fetchAll();

if (isset($_POST['changeBrand'])) {
    $brand = $_POST['changeBrand'];
    $brandName = $_POST['brandName'];
    $stmt = $conn->prepare("SELECT * FROM `carmodels` WHERE `brandID`= $brand");
    $stmt->execute();
    $resultModel = $stmt->fetchAll();
} else {
    $resultModel = "1";
}

if (isset($_POST['addBrand'])) {
    $newBrand = $_POST['addBrand'];
    $stmt = $conn->prepare("INSERT INTO `carbrands`(`brandName`) VALUES ('$newBrand')");
    $stmt->execute();
}
if (isset($_POST['addModel'])) {
    $newBrandID = $_POST['addBrandID'];
    $newModel = $_POST['addModel'];
    $stmt = $conn->prepare("INSERT INTO `carmodels`(`brandID`, `modelName`) VALUES ('$newBrandID', '$newModel')");
    $stmt->execute();

    $stmt = $conn->prepare("SELECT `brandName` FROM `carbrands` WHERE `id`= $newBrandID");
    $stmt->execute();
    $resultView = $stmt->fetchAll();
    $brandName = $resultView[0]['brandName'];

    $stmt = $conn->prepare("SELECT * FROM `carmodels` WHERE `brandID`= $newBrandID");
    $stmt->execute();
    $resultModel = $stmt->fetchAll();
}

if (isset($_POST['editBrand'])) {
    $updateBrandID = $_POST['brandID'];
    $updateBrand = $_POST['editBrand'];
    $stmt = $conn->prepare("UPDATE `carbrands` SET `brandName`='$updateBrand' WHERE `id` = $updateBrandID");
    $stmt->execute();
}

if (isset($_POST['editModel'])) {
    $updateModelID = $_POST['modelID'];
    $updateModel = $_POST['editModel'];
    $stmt = $conn->prepare("UPDATE `carmodels` SET `modelName`='$updateModel' WHERE `id` = $updateModelID");
    $stmt->execute();

    $stmt = $conn->prepare("SELECT `brandID` FROM `carmodels` WHERE `id`= $updateModelID");
    $stmt->execute();
    $resultView = $stmt->fetchAll();
    $brandIDGet = $resultView[0]['brandID'];

    $stmt = $conn->prepare("SELECT `brandName` FROM `carbrands` WHERE `id`= $brandIDGet");
    $stmt->execute();
    $resultView = $stmt->fetchAll();
    $brandName = $resultView[0]['brandName'];

    $stmt = $conn->prepare("SELECT * FROM `carmodels` WHERE `brandID`= $brandIDGet");
    $stmt->execute();
    $resultModel = $stmt->fetchAll();
}

if (isset($_POST['delModelID'])) {

    $delModelID = $_POST['delModelID'];

    $stmt = $conn->prepare("SELECT `brandID` FROM `carmodels` WHERE `id`= $delModelID");
    $stmt->execute();
    $resultView = $stmt->fetchAll();
    $brandIDGet = $resultView[0]['brandID'];

    $stmt = $conn->prepare("SELECT `brandName` FROM `carbrands` WHERE `id`= $brandIDGet");
    $stmt->execute();
    $resultView = $stmt->fetchAll();
    $brandName = $resultView[0]['brandName'];

    $stmt = $conn->prepare("DELETE FROM `carmodels` WHERE `id` = $delModelID");
    $stmt->execute();

    $stmt = $conn->prepare("SELECT * FROM `carmodels` WHERE `brandID`= $brandIDGet");
    $stmt->execute();
    $resultModel = $stmt->fetchAll();
}

if (isset($_POST['delBrandID'])) {

    $delBrandID = $_POST['delBrandID'];

    $stmt = $conn->prepare("DELETE FROM `carmodels` WHERE `brandID` = $delBrandID");
    $stmt->execute();
    $stmt = $conn->prepare("DELETE FROM `carbrands` WHERE `id` = $delBrandID");
    $stmt->execute();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Bootstrap User Management Data Table</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <style>
        .btn-copy {
            background: white !important;
            color: black !important;
            border: none;
        }

        .btn-excel {
            background: rgb(112, 180, 9) !important;
            color: rgb(255, 255, 255) iportant;
            border: none;
        }

        .btn-pdf {
            background: rgb(184, 6, 6) !important;
            color: white !important;
            border: none;
        }

        .btn-print {
            background: rgb(65, 115, 255) !important;
            color: white !important;
            border: none;
        }

        body {
            background-image: url("bg_1@2x.png"), url("Bg_2@2x.png");
            background-repeat: no-repeat;
            background-repeat: no-repeat;
            background-size: 167px, 65%;
            background-position: left bottom, right top;
            font-family: "Exo 2", sans-serif;
        }

        .table-responsive {
            margin: 30px 0;
        }

        .table-wrapper {
            min-width: 1000px;
            background: #fff;
            padding: 20px 25px;
            border-radius: 3px;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
        }

        .table-title {
            padding-bottom: 15px;
            background: #d7b000;
            color: #fff;
            padding: 16px 30px;
            margin: -20px -25px 10px;
            border-radius: 3px 3px 0 0;
        }

        .table-title h2 {
            margin: 5px 0 0;
            font-size: 24px;
        }

        .table-title .btn i {
            float: left;
            font-size: 21px;
            margin-right: 5px;
        }

        .table-title .btn span {
            float: left;
            margin-top: 2px;
        }

        table.table tr th,
        table.table tr td {
            border-color: #e9e9e9;
            padding: 12px 15px;
            vertical-align: middle;
        }

        table.table tr th:first-child {
            width: 60px;
        }

        table.table tr th:last-child {
            width: 100px;
        }

        table.table-striped tbody tr:nth-of-type(odd) {
            background-color: #fcfcfc;
        }

        table.table-striped.table-hover tbody tr:hover {
            background: #f5f5f5;
        }

        table.table th i {
            font-size: 13px;
            margin: 0 5px;
            cursor: pointer;
        }

        table.table td:last-child i {
            opacity: 0.9;
            font-size: 22px;
            margin: 0 5px;
        }

        table.table td a {
            font-weight: bold;
            color: #566787;
            display: inline-block;
            text-decoration: none;
        }

        table.table td a:hover {
            color: #2196f3;
        }

        table.table td a.settings {
            color: #0ab023;
        }

        table.table td a.delete {
            color: #f44336;
        }

        table.table td i {
            font-size: 19px;
        }

        table.table .avatar {
            border-radius: 50%;
            vertical-align: middle;
            margin-right: 10px;
        }

        .status {
            font-size: 30px;
            margin: 2px 2px 0 0;
            display: inline-block;
            vertical-align: middle;
            line-height: 10px;
        }

        .text-success {
            color: #10c469;
        }

        .text-info {
            color: #62c9e8;
        }

        .text-warning {
            color: #ffc107;
        }

        .text-danger {
            color: #ff5b5b;
        }

        .pagination {
            float: right;
            margin: 0 0 5px;
        }

        .pagination li a {
            border: none;
            font-size: 13px;
            min-width: 30px;
            min-height: 30px;
            color: #999;
            margin: 0 2px;
            line-height: 30px;
            border-radius: 2px !important;
            text-align: center;
            padding: 0 6px;
        }

        .pagination li a:hover {
            color: #666;
        }

        .pagination li.active a,
        .pagination li.active a.page-link {
            background: #03a9f4;
        }

        .pagination li.active a:hover {
            background: #0397d6;
        }

        .pagination li.disabled i {
            color: #ccc;
        }

        .pagination li i {
            font-size: 16px;
            padding-top: 6px;
        }

        .hint-text {
            float: left;
            margin-top: 10px;
            font-size: 13px;
        }

        @media print {
            @page {
                size: landscape;
            }

            tr th:last-child,
            tr td:last-child {
                display: none;
            }
        }

        #myInputBrand,
        #myInputModel {
            /* Add a search icon to input */
            background-position: 10px 12px;
            /* Position the search icon */
            background-repeat: no-repeat;
            /* Do not repeat the icon image */
            width: 100%;
            /* Full-width */
            font-size: 16px;
            /* Increase font-size */
            padding: 12px 20px 12px 40px;
            /* Add some padding */
            border: 1px solid #ddd;
            /* Add a grey border */
            margin-bottom: 12px;
            /* Add some space below the input */
        }
    </style>
</head>

<body>
    <script>
        if (localStorage.length == 0) {
            window.location.href = "login.php";
        }
        else if (localStorage.getItem("accType") == "guard") {
            window.location.href = "index.html";
        }
    </script>
    <nav class="navbar fixed-top">
        <div class="container-fluid">
            <img src="csu-logo.png" alt="logocsu" width="25" />
            <a class="navbar-brand" href="index.html"><b class="fs-5">SPS CSU CARIG - ADMIN</b></a>
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
                            <a class="nav-link text-dark active" aria-current="page" href="index.html">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-dark active" aria-current="page" href="dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-dark" aria-current="page" target="_blank"
                                href="signup.php">Register</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-dark" aria-current="page" href="accounts.php">Accounts</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-dark" aria-current="page" href="reports.php">Reports</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-dark" aria-current="page" href="managecars.php">Manage Cars</a>
                        </li>
                        <li class="nav-item">
                            <button class="btn btn-danger w-100" id="logout">Logout</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container" style="height:100vh">
        <div class="row h-100 p-5">
            <div class="col-6 w-50 bg-light h-100 shadow rounded">

                <div class="row p-1">
                    <span class="h2 col-6">Car Brands</span>
                    <div class="col-6">
                        <button class="btn btn-success" id="addBrand">Add Brand</button>
                    </div>
                </div>
                <div>
                    <input type="text" id="myInputBrand" placeholder="Search for Brands..">
                </div>
                <div class="overflow-auto h-75">
                    <ul id="ulBrand">

                        <?php
                        foreach ($resultBrands as $row) {
                            echo '<li class="row mx-0 insideUl"><div class="col-9 p-0"> <form action="managecars.php" method="post"><input type="hidden" name="brandName" value="' . $row['brandName'] . '"><button type="submit" name="changeBrand" value="' . $row['id'] . '" class="btn btn-light w-100 h-100 brandNameText" >' . $row['brandName'] . '</button></form></div>';
                            echo '<div class="col-3 mb-2"><span><button class="btn btn-warning" id="onclick' . $row['id'] . '"><i class="bi bi-pencil-square"></i></button>
                            <button class="btn btn-danger" id="delBrandClick' . $row['id'] . '"><i class="bi bi-trash-fill"></i></button></span></div>';
                            echo '<input type="hidden" id="brandName' . $row['id'] . '" value="' . $row['brandName'] . '"><input type="hidden" id="brandID' . $row['id'] . '" value="' . $row['id'] . '"><hr></li>';
                        }
                        ?>
                    </ul>

                </div>
            </div>
            <div class="col-6 w-50 bg-light h-100 shadow rounded">
                <div class="row p-1 mb-2"><span class="h5 col-6"><?php
                if ($resultModel != "1") {
                    echo $brandName . " ";
                }
                ?>Car Models</span>
                    <div class="col-6">
                        <?php
                        if ($resultModel != "1") {
                            ?>
                            <button class="btn btn-success" id="addModel">Add Model</button>
                        <?php } ?>
                    </div>
                </div>
                <?php
                if ($resultModel != "1") {
                    ?>
                    <div>
                        <input type="text" id="myInputModel" placeholder="Search for <?php
                        if ($resultModel != "1") {
                            echo $brandName . " ";
                        }
                        ?> Models..">
                    </div>
                <?php } ?>
                <div class="overflow-auto h-75">
                    <ul id="ulModel">
                        <?php
                        if ($resultModel != "1") {
                            foreach ($resultModel as $row) {
                                echo '<li class="row mx-0 insideUl"><div class="col-9"><form action="managecars.php" method="post"><input type="hidden" name="modelName" value="' . $row['modelName'] . '"> <button type="button" class="btn btn-light w-100 modelNameText">' . $row['modelName'] . '</button></form></div>';
                                echo '<div class="col-3 mb-2"><span><button class="btn btn-warning" id="onclickModel' . $row['id'] . '"><i class="bi bi-pencil-square"></i></button>
                                    <button class="btn btn-danger" id="delModelClick' . $row['id'] . '"><i class="bi bi-trash-fill"></i></button></span></div>';
                                echo '<input type="hidden" id="modelName' . $row['id'] . '" value="' . $row['modelName'] . '"><input type="hidden" id="modelID' . $row['id'] . '" value="' . $row['id'] . '"><hr></li>';
                            }
                        }
                        ?>
                    </ul>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addBrandModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Brand</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="managecars.php" method="post">
                        <div class="mb-3">
                            <label class="form-label">Brand Name</label>
                            <input type="text" class="form-control" id="addBrand" name="addBrand" />
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            Submit
                        </button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addModelModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add <?php echo $brandName; ?> Model</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="managecars.php" method="post">
                        <div class="mb-3">
                            <label class="form-label">Model Name</label>
                            <input type="text" class="form-control" id="addModel" name="addModel" />
                            <input type="hidden" class="form-control" id="addBrandID" name="addBrandID"
                                value="<?php echo $brand; ?>" />
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            Submit
                        </button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editBrandModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit <span id="brandText"></span></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="managecars.php" method="post">
                        <div class="mb-3">
                            <label class="form-label">Brand Name</label>
                            <input type="text" class="form-control" id="editBrand" name="editBrand" />
                            <input type="hidden" class="form-control" id="brandID" name="brandID"
                                value="<?php echo $brand; ?>" />
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            Submit
                        </button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="delBrandModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                        Are you sure you want to delete
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" disabled class="form-control text-center" id="delBrandText" />
                    <form action="managecars.php" method="post" class="text-center">
                        <div class="mb-3">
                            <input type="hidden" name="delBrandID" id="delBrandID" />
                        </div>
                        <button type="button" class="btn btn-secondary w-25" data-bs-dismiss="modal">
                            NO
                        </button>
                        <button type="submit" class="btn btn-danger w-25">YES</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModelModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit <span id="modelText"></span></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="managecars.php" method="post">
                        <div class="mb-3">
                            <label class="form-label">Model Name</label>
                            <input type="text" class="form-control" id="editModel" name="editModel" />
                            <input type="hidden" class="form-control" id="modelID" name="modelID" />
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            Submit
                        </button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="delModelModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                        Are you sure you want to delete
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" disabled class="form-control text-center" id="delModelText" />
                    <form action="managecars.php" method="post" class="text-center">
                        <div class="mb-3">
                            <input type="hidden" name="delModelID" id="delModelID" />
                        </div>
                        <button type="button" class="btn btn-secondary w-25" data-bs-dismiss="modal">
                            NO
                        </button>
                        <button type="submit" class="btn btn-danger w-25">YES</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $("#logout").click(function () {
            localStorage.clear();
            window.location = "login.php";
        });
        $('#addBrand').click(function () { $('#addBrandModal').modal('toggle'); });
        $('#addModel').click(function () { $('#addModelModal').modal('toggle'); });

        $("#myInputBrand").on("keyup", function (e) {
            var filter = $("#myInputBrand").val().toUpperCase();
            var ul = document.getElementById("ulBrand");
            var li = ul.getElementsByClassName("insideUl");

            for (i = 0; i < li.length; i++) {
                a = li[i].getElementsByClassName("brandNameText")[0];
                txtValue = a.textContent || a.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    li[i].style.display = "";
                } else {
                    li[i].style.display = "none";
                }
            }
        });

        $("#myInputModel").on("keyup", function (e) {
            var filter = $("#myInputModel").val().toUpperCase();
            var ul = document.getElementById("ulModel");
            var li = ul.getElementsByClassName("insideUl");

            for (i = 0; i < li.length; i++) {
                a = li[i].getElementsByClassName("modelNameText")[0];
                txtValue = a.textContent || a.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    li[i].style.display = "";
                } else {
                    li[i].style.display = "none";
                }
            }
        });

        <?php
        foreach ($resultBrands as $row) {
            echo "$('#onclick" . $row['id'] . "').click(function () {";
            echo "$('#editBrand').val($('#brandName" . $row['id'] . "').val());";
            echo "$('#brandID').val($('#brandID" . $row['id'] . "').val());";
            echo "$('#brandText').html($('#brandName" . $row['id'] . "').val());";
            echo "$('#editBrandModal').modal('toggle');});";

        }

        foreach ($resultBrands as $row) {
            echo "$('#delBrandClick" . $row['id'] . "').click(function () {";
            echo "$('#delBrandID').val($('#brandID" . $row['id'] . "').val());";
            echo "$('#delBrandText').val($('#brandName" . $row['id'] . "').val());";
            echo "$('#delBrandModal').modal('toggle');});";

        }

        if ($resultModel != "1") {
            foreach ($resultModel as $row) {
                echo "$('#onclickModel" . $row['id'] . "').click(function () {";
                echo "$('#editModel').val($('#modelName" . $row['id'] . "').val());";
                echo "$('#modelID').val($('#modelID" . $row['id'] . "').val());";
                echo "$('#modelText').html('" . $brandName . "' + ' ' + $('#modelName" . $row['id'] . "').val());";
                echo "$('#editModelModal').modal('toggle');});";
            }

            foreach ($resultModel as $row) {
                echo "$('#delModelClick" . $row['id'] . "').click(function () {";
                echo "$('#delModelID').val($('#modelID" . $row['id'] . "').val());";
                echo "$('#delModelText').val('" . $brandName . "' + ' ' + $('#modelName" . $row['id'] . "').val());";
                echo "$('#delModelModal').modal('toggle');});";

            }
        }
        ?>
    </script>
</body>

</html>