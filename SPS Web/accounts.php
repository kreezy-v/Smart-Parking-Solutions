<?php
include "db.php";
$brandIDAdd = 1;
$view = 1;
if (isset($_POST['delCar'])) {

  $delCar = $_POST['delCar'];

  $stmt = $conn->prepare("DELETE FROM `cardetails` WHERE `referenceId` = $delCar");
  $stmt->execute();
}
if (isset($_POST['changeView'])) {
  $view = $_POST['changeView'];
  if ($view == 1) {
    $stmt = $conn->prepare("SELECT * FROM `accounts` WHERE `accType`='user' OR `accType`='guest'");
    $stmt->execute();
    $result = $stmt->fetchAll();
  } else if ($view == 2) {
    $stmt = $conn->prepare("SELECT * FROM `accounts` WHERE `accType`='ADMIN' OR `accType`='admin' OR `accType`='guard'");
    $stmt->execute();
    $result = $stmt->fetchAll();
  }
} else {
  $stmt = $conn->prepare("SELECT * FROM `accounts` WHERE `accType`='user' OR `accType`='guest'");
  $stmt->execute();
  $result = $stmt->fetchAll();
}

if (isset($_POST['addFNameAdmin'])) {
  $fNameAd = $_POST['addFNameAdmin'];
  $lNameAd = $_POST['addLNameAdmin'];
  $fullNameGuest = $fNameAd . $lNameAd;
  $uAd = $_POST['addUNameAdmin'];
  $pAd = $_POST['addPwordAdmin'];
  $acctTAd = $_POST['addAccTypeAdmin'];
  $stmt = $conn->prepare("INSERT INTO `accounts`(`fName`, `lName`, `sID`, `pword`, `accType`, `slot`) VALUES ('$fNameAd','$lNameAd','$uAd','$pAd','$acctTAd',0)");
  $stmt->execute();

  $stmt = $conn->prepare("SELECT * FROM `accounts` WHERE `accType`='ADMIN' OR `accType`='admin' OR `accType`='guard'");
  $stmt->execute();
  $result = $stmt->fetchAll();
  $view = 2;
}

if (isset($_POST['addRFIDGuest'])) {
  $RFIDguest = $_POST['addRFIDGuest'];
  $fNameAd = $_POST['addFNameGuest'];
  $lNameAd = $_POST['addLNameGuest'];
  $fullNameGuest = $fNameAd . $lNameAd;
  $uAd = $_POST['addUNameGuest'];
  $pAd = $_POST['addPwordGuest'];
  $acctTAd = $_POST['addAccTypeGuest'];
  $stmt = $conn->prepare("INSERT INTO `accounts`(`fName`, `lName`, `sID`, `pword`, `accType`, `slot`, `rfidText`, `fullName` ) VALUES ('$fNameAd','$lNameAd','$uAd','$pAd','$acctTAd',0, '$RFIDguest', '$fullNameGuest')");
  $stmt->execute();
  $view = 1;
}


if (isset($_POST['deleteID'])) {
  $id = $_POST['deleteID'];

  $stmt = $conn->prepare("DELETE FROM `accounts` WHERE id=$id");
  $stmt->execute();

  $stmt = $conn->prepare("SELECT * FROM `accounts` WHERE `accType`='ADMIN' OR `accType`='admin' OR `accType`='guard'");
  $stmt->execute();
  $result = $stmt->fetchAll();
  $view = 2;
}

if (isset($_POST['cBrand'])) {

  $mainId = $_POST['mainId'];
  $cBrand = $_POST['cBrand'];
  $cModel = $_POST['cModel'];
  $platNum = $_POST['pNumber'];
  $stmt = $conn->prepare("INSERT INTO `cardetails`(`accountsId`, `plateNumber`, `carBrandID`, `carModelID`) VALUES ('$mainId','$platNum','$cBrand','$cModel')");
  $stmt->execute();
}

if (isset($_POST['updateID'])) {
  $id = $_POST['updateID'];
  $fName = $_POST['newFName'];
  $lName = $_POST['newLName'];
  $fullName = $fName . " " . $lName;
  $uName = $_POST['newUname'];
  $pWord = $_POST['newPword'];
  $accType = $_POST['newAccType'];
  $stmt = $conn->prepare("UPDATE `accounts` SET `fName` = '$fName', `lName` = '$lName', `fullName`= '$fullName' , `sID` = '$uName', `pword` = '$pWord', `accType` = '$accType'  WHERE id = $id");
  $stmt->execute();

  if ($accType == "admin" || $accType == "guard") {
    $stmt = $conn->prepare("SELECT * FROM `accounts` WHERE `accType`='ADMIN' OR `accType`='admin' OR `accType`='guard'");
    $stmt->execute();
    $result = $stmt->fetchAll();
    $view = 2;
  }
}
if (isset($_POST['deleteIDdate'])) {
  $id = $_POST['deleteIDdate'];

  $stmt = $conn->prepare("DELETE FROM `datefilter` WHERE id=$id");
  $stmt->execute();
}

date_default_timezone_set('Asia/Manila');
// $newTime = date('F j, Y');
$newTime = date('Y-m-d');

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

  <!-- Styles -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
  <!-- Or for RTL support -->
  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
              <a class="nav-link text-dark" aria-current="page" target="_blank" href="signup.php">Register</a>
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
            <div class="col-4">
              <h2>RFID <b>Management</b></h2>
            </div>
            <div class="col-6 d-flex">
              <select class="form-select w-25" aria-label="Default select example" id="changeUser">
                <option value="1" <?php if ($view == 1) {
                  echo "selected";
                } ?>>Users</option>
                <option value="2" <?php if ($view == 2) {
                  echo "selected";
                } ?>>Admins</option>
              </select>
              <?php if ($view == 1) {
                echo "<button class='btn btn-primary w-50 mx-2' id='addGuest'>Add Guest</button>";
              } else if ($view == 2) {
                echo "<button class='btn btn-primary w-50 mx-2' id='addAdmin'>Add Admin</button>";
              } ?>
              <script>
                $('#changeUser').on('change', function () {
                  var url = 'accounts.php';
                  var form = $('<form action="' + url + '" method="post">' +
                    '<input type="hidden" name="changeView" value="' + this.value + '" />' +
                    '</form>');
                  $('body').append(form);
                  form.submit();
                });
              </script>
            </div>
          </div>
        </div>
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <?php
              if ($view == 1) {
                echo "<th>RFID</th><th>Receipt No.</th>";
              }
              ?>
              <th>Username</th>
              <th>Password</th>
              <?php
              if ($view == 1) {
                ?>
                <th>Car Details</th>
                <?php
              }
              ?>
              <th>Account Type</th>
              <th>Date Registered</th>
              <th>Controls</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $count = 0;
            foreach ($result as $row) {
              echo "<tr><td>" . ++$count . "</td><td><a>";
              if ($view == 1) {
                echo $row['fullName'];
              } else if ($view == 2) {
                echo $row['fName'] . " " . $row['lName'];
              }
              echo "</a><input type='hidden' id='id" . $row['id'] . "' value='" . $row['id'] . "' />";
              echo "<input type='hidden' id='fname" . $row['id'] . "' value='" . $row['fName'] . "' /><input type='hidden' id='lname" . $row['id'] . "' value='" . $row['lName'] . "' /> </td>";
              if ($view == 1) {
                echo "<td><span>" . $row['rfidText'] . "</span><input type='hidden' id='rfid" . $row['id'] . "' value='" . $row['rfidText'] . "' /></td>";
                echo "<td><span>" . $row['orNum'] . "</span><input type='hidden' id='orNum" . $row['id'] . "' value='" . $row['orNum'] . "' /></td>";
              }
              echo "<td><span>";
              if ($view == 1) {
                echo $row['sID'];
              } else if ($view == 2) {
                echo $row['sID'];
              }
              echo "</span><input type='hidden' id='user" . $row['id'] . "' value='";
              if ($view == 1) {
                echo $row['sID'];
              } else if ($view == 2) {
                echo $row['sID'];
              }
              echo "' /></td>";
              echo "<td><span>" . (str_repeat("*", strlen($row['pword']))) . "</span><input type='hidden' id='pd" . $row['id'] . "' value='" . $row['pword'] . "' /></td>";

              if ($view == 1 && $row['accType'] != 'guest') {
                echo "<td><span><button class='btn btn-secondary' id='viewCars" . $row['id'] . "'>View</button></span></td>";
              } else if ($view == 1 && $row['accType'] == 'guest') {
                echo "<td></td>";
              }
              echo "<td><span>" . $row['accType'] . "</span><input type='hidden' id='accType" . $row['id'] . "' value='" . $row['accType'] . "' /></td>";
              echo "<td><span>" . $row['regDate'] . "</span><input type='hidden' id='regDate" . $row['id'] . "' value='" . $row['regDate'] . "' /></td>";
              echo "<td><a class='settings' title='Settings' type='button' id='onclick" . $row['id'] . "'><i class='material-icons'>&#xE8B8;</i></a>";
              if ($view == 1) {
                echo "<a href='#' class='delete' title='Delete' id='onDel" . $row['id'] . "'><i class='material-icons'>&#xE5C9;</i></a>";
              } else if ($view == 2 && $row['accType'] != "ADMIN") {
                echo "<a href='#' class='delete' title='Delete' id='onDel" . $row['id'] . "'><i class='material-icons'>&#xE5C9;</i></a>";
              }
              echo "</td></tr>";
            }
            ?>

          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="modal fade" id="viewCarsModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel"><span id="viewCarsText"></span> Cars</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <ul class="col-8 list-group list-group-flush" id="carsTotal">

            </ul>
            <ul class="col-4 list-group list-group-flush" id="carsControls">

            </ul>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-success mx-auto" data-bs-toggle='modal' data-bs-target='#addCarModal'>Add Car</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Close
          </button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="guestModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Add Guest</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="accounts.php" method="post">
            <div class="mb-3">
              <label class="form-label">RFID</label>
              <input type="text" class="form-control" id="addRFIDGuest" name="addRFIDGuest" />
            </div>
            <div class="mb-3">
              <label class="form-label">First Name</label>
              <input type="text" class="form-control" id="addFNameTextGuest" name="addFNameGuest" />
            </div>
            <div class="mb-3">
              <label class="form-label">Last Name</label>
              <input type="text" class="form-control" id="addLNameTextGuest" name="addLNameGuest" />
            </div>
            <div class="mb-3">
              <label class="form-label">Username</label>
              <input type="text" class="form-control" id="addGuestText" name="addUNameGuest" />
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="text" class="form-control" id="addPDTextGuest" name="addPwordGuest" />
              <input type="hidden" class="form-control" id="addAccTypeGuest" name="addAccTypeGuest" value="guest" />
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
  <div class="modal fade" id="adminModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Add Admin</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="accounts.php" method="post">
            <div class="mb-3">
              <label class="form-label">First Name</label>
              <input type="text" class="form-control" id="addFNameTextAdmin" name="addFNameAdmin" />
            </div>
            <div class="mb-3">
              <label class="form-label">Last Name</label>
              <input type="text" class="form-control" id="addLNameTextAdmin" name="addLNameAdmin" />
            </div>
            <div class="mb-3">
              <label class="form-label">Username</label>
              <input type="text" class="form-control" id="addAdminText" name="addUNameAdmin" />
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="text" class="form-control" id="addPDTextAdmin" name="addPwordAdmin" />

            </div>
            <div class="mb-3"><label class="form-label">Account Type</label> <select class="form-select"
                aria-label="Default select example" id="updateAccTypeText" name="addAccTypeAdmin">
                <option value="admin">admin</option>
                <option value="guard">guard</option>
              </select></div>
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
  <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">UPDATE</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="accounts.php" method="post">
            <div class="mb-3">
              <input type="hidden" name="updateID" id="updateIDText" />
              <label class="form-label">First Name</label>
              <input type="text" class="form-control" id="updateFNameText" name="newFName" />
            </div>
            <div class="mb-3">
              <label class="form-label">Last Name</label>
              <input type="text" class="form-control" id="updateLNameText" name="newLName" />
            </div>
            <?php
            if ($view == 1) {
              echo '<div class="mb-3"><label class="form-label">RFID</label><input type="text" disabled class="form-control" id="updateRFIDText" /></div>';
            }
            ?>
            <div class="mb-3">
              <label class="form-label">Username</label>
              <input type="text" class="form-control" id="updateUSERText" name="newUname" />
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <div class="d-flex">
                <input type="password" class="form-control" id="updatePDText" name="newPword" />
                <button type="button" class="btn btn-light" onclick="seen()">
                  <i class="bi bi-eye-fill" id="passIcon" style="color:green"></i>
                </button>
              </div>
            </div>



            <?php
            if ($view == 1) {
              echo '<div class="mb-3"><label class="form-label">Account Type</label> <select class="form-select" aria-label="Default select example" id="updateAccTypeText" name="newAccType"><option value="user">user</option><option value="guest">guest</option></select></div>';
            } else if ($view == 2 && $row['accType'] != "ADMIN") {
              echo '<div class="mb-3"><label class="form-label">Account Type</label> <select class="form-select" aria-label="Default select example" id="updateAccTypeText" name="newAccType"><option value="admin">admin</option><option value="guard">guard</option></select></div>';
            }
            ?>

            <div class="mb-3">
              <label class="form-label">Date Registered</label>
              <input type="text" disabled class="form-control" id="updateRegDateText" />
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
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">
            Are you sure you want to delete
          </h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="text" disabled class="form-control text-center" id="deleteText" />
          <form action="accounts.php" method="post" class="text-center">
            <div class="mb-3">
              <input type="hidden" name="deleteID" id="deleteIDText" />
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

  <div class="modal fade" id="addCarModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Add Car</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="accounts.php" method="post">
            <div class="form-group my-1">
              <input type="hidden" id="mainId" name="mainId">
              <label for="lName">Car Brand <sup>*</sup></label>
              <select class="select-brand" id="cBrand" name="cBrand">
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
              <select class="select-model" id="cModel" name="cModel">
                <?php
                $stmt = $conn->prepare("SELECT * FROM `carmodels` WHERE `brandID` = $brandIDAdd");
                $stmt->execute();
                $resultModels = $stmt->fetchAll();

                foreach ($resultModels as $row) {
                  echo "<option value='" . $row['id'] . "'>" . $row['modelName'] . "</option>";
                }
                ?>
              </select>

            </div>
            <div class="form-group my-1">
              <label>Plate Number <sup>*</sup></label>
              <input type="text" class="form-control" id="pNumber" name="pNumber" placeholder="Plate Number">
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
  <link
    href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-2.0.8/b-3.0.2/b-html5-3.0.2/b-print-3.0.2/datatables.min.css"
    rel="stylesheet" />

  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
  <script
    src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-2.0.8/b-3.0.2/b-html5-3.0.2/b-print-3.0.2/datatables.min.js"></script>
  <script>
    $(document).ready(function () {
      {
        $('.select-model').select2({
          theme: 'bootstrap-5'
        });
      }
    });
    $(document).ready(function () {
      {
        $('.select-brand').select2({
          theme: 'bootstrap-5'
        });
      }
    });
    $("#logout").click(function () {
      localStorage.clear();
      window.location = "login.php";
    });
    $(document).ready(function () {
      var table = $("table").DataTable();

      $('.select-brand').select2({
        theme: 'bootstrap-5'
      });
    });
    var listprint = "";
    var listcontrol = "";
    <?php
    foreach ($result as $row) {
      echo "$('#onclick" . $row['id'] . "').click(function () {";
      echo "$('#updateIDText').val($('#id" . $row['id'] . "').val());";
      echo "$('#updateFNameText').val($('#fname" . $row['id'] . "').val());";
      echo "$('#updateLNameText').val($('#lname" . $row['id'] . "').val());";
      if ($view == 1) {
        echo "$('#updateRFIDText').val($('#rfid" . $row['id'] . "').val());";
      }
      echo "$('#updateUSERText').val($('#user" . $row['id'] . "').val());";
      echo "$('#updatePDText').val($('#pd" . $row['id'] . "').val());";
      echo "$('#updateAccTypeText').val($('#accType" . $row['id'] . "').val());";
      echo "$('#updateRegDateText').val($('#regDate" . $row['id'] . "').val());";
      echo "$('#updateModal').modal('toggle');});";

    }

    foreach ($result as $row) {
      $idAcc = $row['id'];
      echo "$('#viewCars" . $row['id'] . "').click(function () {";
      echo "$('#mainId').val($idAcc);";
      echo "$('#viewCarsText').html($('#fname" . $row['id'] . "').val() + '\'s');";
      $stmt = $conn->prepare("SELECT * FROM `cardetails` WHERE `accountsId`= $idAcc");
      $stmt->execute();
      $resultDetails = $stmt->fetchAll();

      $listPrint = "";
      $listControl = "";
      foreach ($resultDetails as $details) {
        $detailId = $details['referenceId'];
        $brandUser = $details['carBrandID'];
        $modelUser = $details['carModelID'];

        $stmt = $conn->prepare("SELECT * FROM `carbrands` WHERE `id`= $brandUser");
        $stmt->execute();
        $resultBrandUser = $stmt->fetchAll();

        $stmt = $conn->prepare("SELECT * FROM `carmodels` WHERE `id`= $modelUser");
        $stmt->execute();
        $resultModelUser = $stmt->fetchAll();

        $listPrint .= '<li class="list-group-item">' . $resultBrandUser[0]['brandName'] . " " . $resultModelUser[0]['modelName'] . '</li>';

        $listControl .= '<li class="list-group-item"><span><form method="post" action="accounts.php"><button class="btn btn-danger" type="submit" name="delCar" value="' . $detailId . '"><i class="bi bi-trash-fill"></i></button></form></span</li>';

      }
      echo "listprint = '" . $listPrint . "';";
      echo "listcontrol = '" . $listControl . "';";
      echo "$('#carsTotal').html(listprint);";
      echo "$('#carsControls').html(listcontrol);";
      echo "$('#viewCarsModal').modal('toggle'); });";
    }
    foreach ($result as $row) {

      echo " $('#onDel" . $row['id'] . "').click(function () {";
      echo "$('#deleteIDText').val($('#id" . $row['id'] . "').val());";
      if ($view == 1) {
        echo "$('#deleteText').val('" . $row['fullName'] . "');";
      } else if ($view == 2) {
        echo "$('#deleteText').val('" . $row['fName'] . " " . $row['lName'] . "');";
      }
      echo "$('#deleteModal').modal('toggle');});";
    }
    ?>

    $('#addAdmin').click(function () { $('#adminModal').modal('toggle'); });
    var passIcon = $("#passIcon");
    function seen() {
      if (passIcon.hasClass("bi bi-eye-fill")) {
        passIcon.removeClass("bi bi-eye-fill");
        passIcon.addClass("bi bi-eye-slash-fill");
        passIcon.css("color", "red")
        $("#updatePDText").prop("type", "text");
      }
      else if (passIcon.hasClass("bi bi-eye-slash-fill")) {
        passIcon.removeClass("bi bi-eye-slash-fill");
        passIcon.addClass("bi bi-eye-fill");
        passIcon.css("color", "green")
        $("#updatePDText").prop("type", "password");
      }
    }

    $('#addGuest').click(function () { $('#guestModal').modal('toggle'); });

  </script>
</body>

</html>