<?php
include "db.php";

date_default_timezone_set('Asia/Manila');
$newTime = date('Y-m-d');
// $stmt = $conn->prepare("SELECT accounts.id AS id, accounts.rfidText, accounts.orNum, accounts.fullName, accounts.fName, accounts.lName, accounts.sID, accounts.pword, accounts.accType, accounts.regDate, cardetails.plateNumber, cardetails.carBrandID, cardetails.carModelID FROM accounts LEFT JOIN cardetails ON accounts.id = cardetails.accountsId WHERE accounts.accType IN ('user', 'guest');");
// $stmt->execute();
// $result = $stmt->fetchAll();
if (isset($_POST['dateFilter']) && ($_POST['dateFilter'] != "")) {
    $dateFilter = $_POST['dateFilter'];
    $stmt = $conn->prepare("SELECT * FROM `datefilter` WHERE `recentDate` = '$dateFilter'");
    $stmt->execute();
    $resultDate = $stmt->fetchAll();

    if ($stmt->rowCount() > 0) {
        $accResult = "SELECT accounts.id AS id, accounts.rfidText, accounts.fullName, datefilter.recentDate, datefilter.tIn, datefilter.tOut, datefilter.parkedSlot, accounts.accType ";
        $accResult .= "FROM accounts JOIN datefilter ON accounts.id = datefilter.id WHERE ";
        foreach ($resultDate as $row) {
            $accResult .= "(accounts.id = " . $row['id'] . " AND datefilter.recentDate = '" . $dateFilter . "') OR ";
        }
        $accResult .= "0";
        $stmt = $conn->prepare($accResult);
        $stmt->execute();
        $result = $stmt->fetchAll();
    } else {
        $result = array();
    }
} else if ((isset($_POST['dateFilterRange1']) && ($_POST['dateFilterRange1'] != "")) && (isset($_POST['dateFilterRange2']) && ($_POST['dateFilterRange2'] != ""))) {
    $dateFilterRange1 = $_POST['dateFilterRange1'];
    $dateFilterRange2 = $_POST['dateFilterRange2'];
    $stmt = $conn->prepare("SELECT * FROM `datefilter` WHERE `recentDate` BETWEEN '$dateFilterRange1' AND '$dateFilterRange2'");
    $stmt->execute();
    $resultDate = $stmt->fetchAll();

    if ($stmt->rowCount() > 0) {
        $accResult = "SELECT accounts.id AS id, accounts.rfidText, accounts.fullName, datefilter.recentDate, datefilter.tIn, datefilter.tOut, datefilter.parkedSlot, accounts.accType ";
        $accResult .= "FROM accounts JOIN datefilter ON accounts.id = datefilter.id WHERE ";
        foreach ($resultDate as $row) {
            $accResult .= "(accounts.id = " . $row['id'] . " AND datefilter.recentDate BETWEEN '" . $dateFilterRange1 . "' AND '" . $dateFilterRange2 . "') OR ";
        }
        $accResult .= "0";
        $stmt = $conn->prepare($accResult);
        $stmt->execute();
        $result = $stmt->fetchAll();
    } else {
        $result = array();
    }
} else {
    $stmt = $conn->prepare("SELECT * FROM `datefilter` WHERE `recentDate` = '$newTime'");
    $stmt->execute();
    $resultDate = $stmt->fetchAll();

    if ($stmt->rowCount() > 0) {
        $accResult = "SELECT accounts.id AS id, accounts.rfidText, accounts.fullName, datefilter.recentDate, datefilter.tIn, datefilter.tOut, datefilter.parkedSlot, accounts.accType ";
        $accResult .= "FROM accounts JOIN datefilter ON accounts.id = datefilter.id WHERE ";
        foreach ($resultDate as $row) {
            $accResult .= "(accounts.id = " . $row['id'] . " AND datefilter.recentDate = '" . $newTime . "') OR ";
        }
        $accResult .= "0";
        $stmt = $conn->prepare($accResult);
        $stmt->execute();
        $result = $stmt->fetchAll();
    } else {
        $result = array();
    }
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
    <div class="container mt-5">
        <div class="table-responsive pdf-page">
            <div class="table-wrapper">
                <div class="table-title" id="exampleWrapper">
                    <div class="row">
                        <div class="col-2">
                            <h2><b>REPORTS</b></h2>
                        </div>
                        <div class="col-6 d-flex">
                            <select class="form-select w-25" aria-label="Default select example" id="changeDate">
                                <option <?php
                                if (isset($_POST['dateFilter']) && ($_POST['dateFilter'] != "")) {
                                    echo "selected";
                                }
                                ?> value="1">Daily</option>
                                <option <?php
                                if ((isset($_POST['dateFilterRange1']) && ($_POST['dateFilterRange1'] != "")) && (isset($_POST['dateFilterRange2']) && ($_POST['dateFilterRange2'] != ""))) {
                                    echo "selected";
                                }
                                ?> value="2">Weekly/Monthly/Yearly</option>
                            </select>
                            <form action="reports.php" method="post" class="d-flex w-100 mx-1" id="daily">
                                <input id="datepicker" name="dateFilter" />
                                <button class="btn btn-primary w-50 mx-2" type="submit">Filter</button>
                            </form>
                            <form action="reports.php" method="post" class="d-flex w-100 mx-1" id="range"
                                style="display: none !important;">
                                <input id="datepickerRange1" name="dateFilterRange1" />
                                <input id="datepickerRange2" name="dateFilterRange2" />
                                <button class="btn btn-primary w-50 mx-2" type="submit">Filter</button>
                            </form>
                            <script>
                                var datePicker = $('#datepicker').datepicker({
                                    uiLibrary: 'bootstrap5',
                                    format: 'yyyy-mm-dd'
                                }).next(".btn-outline-secondary").addClass("btn-danger text-white");

                                var datePickerWeek1 = $('#datepickerRange1').datepicker({
                                    uiLibrary: 'bootstrap5',
                                    format: 'yyyy-mm-dd'
                                }).next(".btn-outline-secondary").addClass("btn-danger text-white");

                                var datePickerRange2 = $('#datepickerRange2').datepicker({
                                    uiLibrary: 'bootstrap5',
                                    format: 'yyyy-mm-dd'
                                }).next(".btn-outline-secondary").addClass("btn-danger text-white");

                                $(".gj-datepicker").removeClass("mb-3");

                                <?php
                                if (isset($_POST['dateFilter']) && ($_POST['dateFilter'] != "")) {
                                    echo "$('#datepicker').val('$dateFilter');";
                                    echo "$('#daily').attr('style', 'display: flex !important');";
                                    echo "$('#range').attr('style', 'display: none !important');";

                                } else if ((isset($_POST['dateFilterRange1']) && ($_POST['dateFilterRange1'] != "")) && (isset($_POST['dateFilterRange2']) && ($_POST['dateFilterRange2'] != ""))) {

                                    echo "$('#datepickerRange1').val('$dateFilterRange1');";
                                    echo "$('#datepickerRange2').val('$dateFilterRange2');";
                                    echo "$('#range').attr('style', 'display: flex !important');";
                                    echo "$('#daily').attr('style', 'display: none !important');";

                                } else {
                                    echo "$('#datepicker').val('$newTime');";
                                }
                                ?>
                            </script>
                        </div>

                        <div class="col-3"></div>
                    </div>
                    <div class="row mb-0"><span class="h5 mb-0">Total # of Cars: <?php echo $stmt->rowCount(); ?></span>
                    </div>
                </div>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>RFID</th>
                            <th>Account Type</th>
                            <th>Plate Number</th>
                            <th>Car Brand</th>
                            <th>Car Model</th>
                            <th>Parked Slot</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                            <th class="px-5">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $count = 0;
                        $plateCounter = 246;

                        $cars = ["Corolla", "Wigo", "Hilux"];
                        foreach ($result as $row) {
                            $plateCounter++;
                            echo "<tr><td>" . ++$count . "</td><td><a>" . $row['fullName'] . "</a></td>";
                            echo "<td><span>" . $row['rfidText'] . "</span></td>";
                            echo "<td><span>" . $row['accType'] . "</span></td>";
                            echo "<td><span>ABC $plateCounter</span></td>";
                            echo "<td><span>Toyota</span></td>";
                            echo "<td><span>" . $cars[$count] . "</span></td>";
                            // if ($view == 1) {
                            //     echo "<td><span>" . $row['plateNumber'] . "</span><input type='hidden' id='plateNumber" . $row['id'] . "' value='" . $row['plateNumber'] . "' /></td>";
                            //     if ($row['carBrandID'] != null) {
                            //         $prepareNow = "SELECT `brandName` FROM `carbrands` WHERE `id`= " . $row['carBrandID'];
                            //         $stmt = $conn->prepare($prepareNow);
                            //         $stmt->execute();
                            //         $resultBrand = $stmt->fetchAll();
                            //         $carBrandNow = $resultBrand[0]['brandName'];
                            //     } else {
                            //         $carBrandNow = "";
                            //     }
                        
                            //     if ($row['carModelID'] != null) {
                            //         $prepareNow = "SELECT `modelName` FROM `carmodels` WHERE `id`= " . $row['carModelID'];
                            //         $stmt = $conn->prepare($prepareNow);
                            //         $stmt->execute();
                            //         $resultModel = $stmt->fetchAll();
                            //         $carModelNow = $resultModel[0]['modelName'];
                            //     } else {
                            //         $carModelNow = "";
                            //     }
                        
                            //     echo "<td><span>" . $carBrandNow . "</span><input type='hidden' id='carBrand" . $row['id'] . "' value='" . $row['carBrandID'] . "' /></td>";
                            //     echo "<td><span>" . $carModelNow . "</span><input type='hidden' id='carModel" . $row['id'] . "' value='" . $row['carModelID'] . "' /></td>";
                            // }
                            echo "<td><span>";
                            if ($row['parkedSlot'] == '0') {
                                echo "None";
                            } else {
                                echo $row['parkedSlot'];
                            }
                            echo "</span></td>";
                            echo " <td><span>";
                            if ($row['tIn'] == '') {
                                echo "N/A";
                            } else {
                                echo $row['tIn'];
                            }
                            echo "</span></td>";
                            echo " <td><span>";
                            if ($row['tOut'] == '') {
                                echo "N/A";
                            } else {
                                echo $row['tOut'];
                            }
                            echo "</span></td>";
                            echo "<td>" . $row['recentDate'] . "</td></tr>";

                        }
                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <link
        href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-2.0.8/b-3.0.2/b-html5-3.0.2/b-print-3.0.2/datatables.min.css"
        rel="stylesheet" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script
        src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-2.0.8/b-3.0.2/b-html5-3.0.2/b-print-3.0.2/datatables.min.js"></script>
    <script>
        $("#logout").click(function () {
            localStorage.clear();
            window.location = "login.php";
        });
        $('#changeDate').on('change', function () {
            if (this.value == 1) {
                $('#daily').attr("style", "display: flex !important");
                $('#range').attr("style", "display: none !important");
            }
            else if (this.value == 2) {
                $('#range').attr("style", "display: flex !important");
                $('#daily').attr("style", "display: none !important");
            }
        });
        $(document).ready(function () {
            var table = $("table").DataTable({
                buttons: [
                    {
                        extend: "copy",
                        className: "btn-copy",
                        title: "SPS RFID Management",
                    },
                    {
                        extend: "excel",
                        className: "btn-excel",
                        title: "SPS RFID Management",
                        customize: function (xlsx) {
                            var sheet = xlsx.xl.worksheets['sheet1.xml'];
                            var downrows = 4;

                            var clRow = $('row', sheet);
                            //update Row
                            clRow.each(function () {
                                var attr = $(this).attr('r');
                                var ind = parseInt(attr);
                                ind = ind + downrows;
                                $(this).attr("r", ind);
                            });

                            // Update  row > c
                            $('row c ', sheet).each(function () {
                                var attr = $(this).attr('r');
                                var pre = attr.substring(0, 1);
                                var ind = parseInt(attr.substring(1, attr.length));
                                ind = ind + downrows;
                                $(this).attr("r", pre + ind);
                            });




                            function Addrow(index, data) {
                                msg = '<row r="' + index + '">'
                                for (i = 0; i < data.length; i++) {
                                    var key = data[i].k;
                                    var value = data[i].v;
                                    msg += '<c t="inlineStr" s="51" r="' + key + index + '">';
                                    msg += '<is>';
                                    msg += '<t>' + value + '</t>';
                                    msg += '</is>';
                                    msg += '</c>';
                                }
                                msg += '</row>';
                                return msg;
                            }

                            var r1 = Addrow(1, [{ k: 'A', v: 'SPS RFID Management of CSU Carig' }]);
                            var r2 = Addrow(2, [{ k: 'B', v: 'Total Cars Parked: ' }, { k: 'C', v: '<?php echo $stmt->rowCount(); ?>' }]);
                            var r3 = Addrow(3, [{ k: 'B', v: 'Date From: ' }, {
                                k: 'C', v: '<?php
                                if (isset($_POST['dateFilter']) && ($_POST['dateFilter'] != "")) {
                                    echo $dateFilter;
                                } else if ((isset($_POST['dateFilterRange1']) && ($_POST['dateFilterRange1'] != "")) && (isset($_POST['dateFilterRange2']) && ($_POST['dateFilterRange2'] != ""))) {

                                    echo $dateFilterRange1;

                                } else {
                                    echo $newTime;
                                }
                                ?>' }]);
        var r4 = Addrow(4, [{ k: 'B', v: 'To:' }, {
            k: 'C', v: '<?php
            if (isset($_POST['dateFilter']) && ($_POST['dateFilter'] != "")) {
                echo $dateFilter;
            } else if ((isset($_POST['dateFilterRange1']) && ($_POST['dateFilterRange1'] != "")) && (isset($_POST['dateFilterRange2']) && ($_POST['dateFilterRange2'] != ""))) {

                echo $dateFilterRange2;

            } else {
                echo $newTime;
            }
            ?>' }]);

        sheet.childNodes[0].childNodes[1].innerHTML = r1 + r2 + r3 + r4 + sheet.childNodes[0].childNodes[1].innerHTML;
        var cellA5 = $('row c[r^="A5"]', sheet); // Assuming A1 is the cell you want to modify
        cellA5.text(''); // Replace 'New Value' with your desired new value


                        }
                    },
        {
            extend: "pdf",
                className: "btn-pdf",
                    title: "SPS RFID Management",
                    },
        {
            extend: "print",
                className: "btn-print",
                    title: "SPS RFID Management",
                    },
                ],
            });
        table.buttons().container().appendTo("#exampleWrapper .col-3");
        });
    </script>
</body>

</html>