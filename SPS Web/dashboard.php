<?php
include "db.php";
date_default_timezone_set('Asia/Manila');

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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <style>
        body {
            background-image: url("bg_1@2x.png"), url("Bg_2@2x.png");
            background-repeat: no-repeat;
            background-repeat: no-repeat;
            background-size: 167px, 65%;
            background-position: left bottom, right top;
            font-family: "Exo 2", sans-serif;
        }
    </style>
</head>

<body style="background-color: azure">
    <script>
        if (localStorage.length == 0) {
            window.location.href = "login.php";
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

    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-3 p-4">
                <div class="card">
                    <div class="card-body pb-0">
                        <span class="d-flex justify-content-between">
                            <span class="p-0">
                                <p class="text-primary mb-0">ACCOUNTS REGISTERED</p>
                                <p class="fs-2 my-0">
                                    <?php
                                    $stmt = $conn->prepare("SELECT * FROM `accounts`");
                                    $stmt->execute();
                                    echo $stmt->rowCount();
                                    ?>
                                </p>
                            </span>
                            <span class="h1 my-auto">
                                <i class="bi bi-person-fill"></i>
                            </span>
                        </span>

                    </div>
                </div>
            </div>
            <div class="col-3 p-4">
                <div class="card">
                    <div class="card-body pb-0">
                        <span class="d-flex justify-content-between">
                            <span class="p-0">
                                <p class="text-primary mb-0">TOTAL CARS PARKED (2024)</p>
                                <p class="fs-2 my-0">
                                    <?php
                                    $year_start = strtotime('first day of January', time());
                                    $year_end = strtotime('last day of December', time());
                                    $year_start = date('Y-m-d', $year_start);
                                    $year_end = date('Y-m-d', $year_end);
                                    $stmt = $conn->prepare("SELECT * FROM `datefilter` WHERE `recentDate` BETWEEN '$year_start' AND '$year_end'");
                                    $stmt->execute();
                                    echo $stmt->rowCount();
                                    ?>
                                </p>
                            </span>
                            <span class="h1 my-auto">
                                <i class="bi bi-car-front-fill"></i>
                            </span>
                        </span>

                    </div>
                </div>
            </div>
            <div class="col-3 p-4">
                <div class="card">
                    <div class="card-body pb-0">
                        <span class="d-flex justify-content-between">
                            <span class="p-0">
                                <p class="text-primary mb-0">AVAILABLE CAR SPACE</p>
                                <p class="fs-2 my-0">
                                    <?php
                                    $stmt = $conn->prepare("SELECT `cars` FROM `slot`");
                                    $stmt->execute();
                                    $resultCar = $stmt->fetchAll();
                                    $car1 = $resultCar[0]['cars'][1];
                                    $car2 = $resultCar[0]['cars'][2];
                                    $car3 = $resultCar[0]['cars'][3];

                                    echo 3 - ($car1 + $car2 + $car3);
                                    ?>
                                </p>
                            </span>
                            <span class="h1 my-auto">
                                <i class="bi bi-grid-3x2"></i>
                            </span>
                        </span>

                    </div>
                </div>
            </div>
            <div class="col-3 p-4">
                <div class="card">
                    <div class="card-body pb-0">
                        <span class="d-flex justify-content-between">
                            <span class="p-0">
                                <p class="text-primary mb-0">TODAY'S GUEST</p>
                                <p class="fs-2 my-0">
                                    <?php
                                    $newTime = date('Y-m-d');
                                    $stmt = $conn->prepare("SELECT `id` FROM `dateFilter` WHERE `recentDate` = '$newTime' AND (`id` = 3 OR `id` = 4)");
                                    $stmt->execute();

                                    echo $stmt->rowCount();
                                    ?>
                                </p>
                            </span>
                            <span class="h1 my-auto">
                                <i class="bi bi-person-fill"></i>
                            </span>
                        </span>

                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 px-5">
                <div class="row bg-white rounded shadow w-75 mx-auto">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>

    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    <script>
        $("#logout").click(function () {
            localStorage.clear();
            window.location = "login.php";
        });
        var xValues = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        <?php

        $countY = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        for ($i = 1; $i < 13; $i++) {
            $endOfDate = "2024-" . $i . "-01";
            $date = new DateTime($endOfDate);
            $date->modify('last day of this month');
            $currDate = $date->format('Y-m-d');

            $stmt = $conn->prepare("SELECT `id` FROM `dateFilter` WHERE `recentDate` BETWEEN '$endOfDate' AND '$currDate'");
            $stmt->execute();
            $countY[$i - 1] = $stmt->rowCount();
        }

        echo "var yValues = [" . implode(",", $countY) . "];";
        ?>

        var barColors = ["orange", "orange", "orange", "orange", "orange", "orange", "orange", "orange", "orange", "orange", "orange", "orange"];

        new Chart("barChart", {
            type: "bar",
            data: {
                labels: xValues,
                datasets: [{
                    backgroundColor: barColors,
                    data: yValues
                }]
            },
            options: {
                legend: { display: false },
                title: {
                    display: true,
                    text: "Monthly Cars Parked (2024)"
                }
            }
        });


    </script>
</body>

</html>