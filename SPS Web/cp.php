<?php

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['rfid'])) {


    $rfid = "csu" . $_POST['rfid'];
    $fName = $_POST['fName'];
    $lName = $_POST['lName'];
    $fullName = $fName . " " . $lName;
    $sID = $_POST['sID'];
    $pWord = $_POST['pword'];
    $orNum = $_POST['orNum'];
    $cBrand = $_POST['cBrand'];
    $cModel = $_POST['cModel'];
    $platNum = $_POST['pNumber'];

    $stmt = $conn->prepare("INSERT INTO `accounts`(`rfidText`, `fullName`, `sID`, `pword`, `slot`, `orNum`, `fName`, `lName`, `accType` ) VALUES ('$rfid','$fName','$sID','$pWord','0', '$orNum', '$fName', '$lName', 'user')");
    $stmt->execute();
    $stmt = $conn->prepare("SELECT Max(id) FROM `accounts`");
    $stmt->execute();
    $resultId = $stmt->fetchAll();

    $maxId = $resultId[0][0];
    $stmt = $conn->prepare("INSERT INTO `cardetails`(`accountsId`, `plateNumber`, `carBrandID`, `carModelID`) VALUES ('$maxId','$platNum','$cBrand','$cModel')");
    $stmt->execute();

  } else if (isset($_POST['sIDlog'])) {
    $sID = $_POST['sIDlog'];
    $pWord = $_POST['pword'];
    $stmt = $conn->prepare("SELECT * FROM `accounts` WHERE `sID` = '$sID' AND `pword` = '$pWord'");
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
      $result = $stmt->fetchAll();
      $localStore = $result[0]['id'];
      echo "<script>localStorage.setItem('id', '" . $result[0]['id'] . "'); localStorage.setItem('sID', '" . $result[0]['sID'] . "'); localStorage.setItem('pword', '" . $result[0]['pword'] . "');</script>";
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
                <button type="button" class="btn btn-danger w-100" onclick="outNa()">LOGOUT</button>
                <script>function outNa() { localStorage.removeItem("id"); localStorage.removeItem("sID"); localStorage.removeItem("pword"); window.location.href = "cp.php"; }</script>
              </li>
            </div>
          </ul>
        </div>
      </div>
    </div>
  </nav>
  <div class="container mt-5 pt-3">

    <div id="access">
      <div class="row align-items-center my-t py-3 px-0 px-0" id="slotNum">
        <div class="row bg-dark text-white mx-0">
          <div class="col text-center h4 mb-0">Last Time In</div>
          <div class="col text-center h4 mb-0">Last Time Out</div>
        </div>
        <div class="row bg-dark text-white mx-0">
          <div class="col text-center h3 mb-0" id="updateTin"></div>
          <div class="col text-center h3 mb-0" id="updateTout"></div>
        </div>
        <?php
        $stmt = $conn->prepare("SELECT slotNumber FROM `slot` WHERE id = 0");
        $stmt->execute();
        $result = $stmt->fetchAll();
        ?>
        <div class="title-wrapper mt-5">
          <h1 class="sweet-title mt-5">
            <span data-text="<?php

            if ($result[0]['slotNumber'] == 0) {
              echo "FULL";
            } else {

              echo $result[0]['slotNumber'];
            }
            ?>">
              <?php

              if ($result[0]['slotNumber'] == 0) {
                echo "FULL";
              } else {

                echo $result[0]['slotNumber'];
              }
              ?>
            </span>
          </h1>
        </div>
      </div>
      <script>
        setInterval(function () {
          $.ajax({
            url: 'test.php',
            type: 'POST',
            data: { id: localStorage.getItem('id') },
            success: function (response) {
              $("#slotNum").html(response);
            },
            error: function (xhr, status, error) {
              console.error('Error:', error);
            }
          });
          // $("#slotNum").load("test.php");
        }, 500);

      </script>

    </div>
    <div class="row">
      <div class="col text-center h1 mb-0" id="updateTextLog"></div>
    </div>

    <div class="row fixed-bottom mx-4 my-5 z-n1">
      <div class="col text-center">
        <a type="button" class="btn btn-warning w-100" href="talkting.html">
          <i class="bi bi-chat-fill" style="font-size: 100px"></i><br>
          <span class="fs-2">FAQ</span>
        </a>

      </div>
      <div class="col text-center">
        <a type="button" class="btn btn-warning w-100" href="information.html">
          <i class="bi bi-exclamation-circle-fill" style="font-size: 100px"></i>
          <br>
          <span class="fs-2">ABOUT</span>
        </a>
      </div>
    </div>
  </div>
  <div class="modal fade" id="loginModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content bg-warning">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel"><b>LOGIN</b></h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="cp.php" method="POST">
            <div class="form-group my-1">
              <label for="sID">School ID <sup>*</sup></label>
              <input type="text" class="form-control" id="sID" name="sIDlog" placeholder="School ID Example(20-20055)">
            </div>
            <div class="form-group my-1">
              <label for="password">Password <sup>*</sup></label>
              <div class="col-12 d-flex">
                <input type="password" class="form-control" id="password" name="pword" placeholder="Password">
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
  <script src="script.js"></script>
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