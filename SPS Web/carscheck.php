<?php
include "db.php";
$brandIDAdd = 1;
$mainCar = 0;
if (isset($_POST['id'])) {
    $userId = $_POST['id'];
    $stmt = $conn->prepare("SELECT * FROM `accounts` WHERE `id` = $userId");
    $stmt->execute();
    $result = $stmt->fetchAll();
}
?>
<h2 class="h2 text-center mt-5">
    <b>Main Car</b>
    <p class="h3">
        <?php
        if (isset($_POST['id'])) {
            $mainCar = $result[0]['mainCar'];
            $stmt = $conn->prepare("SELECT * FROM `cardetails` WHERE `referenceId` = $mainCar");
            $stmt->execute();
            $result = $stmt->fetchAll();

            $brandID = $result[0]['carBrandID'];
            $modelID = $result[0]['carModelID'];
            $stmt = $conn->prepare("SELECT * FROM `carbrands` WHERE `id` = $brandID");
            $stmt->execute();
            $resultBrand = $stmt->fetchAll();

            $stmt = $conn->prepare("SELECT * FROM `carmodels` WHERE `id` = $modelID");
            $stmt->execute();
            $resultModel = $stmt->fetchAll();

            echo $resultBrand[0]['brandName'] . " " . $resultModel[0]['modelName'];
        }
        ?>
    </p>
    <p class="h3">
        <?php
        if (isset($_POST['id'])) {
            echo "(Plate Number: " . $result[0]['plateNumber'] . ")";
        }
        ?>
    </p>
</h2>

<div class="container bg-white rounded shadow overflow-auto" style="height:60vh">
    <div class="row">
        <div class="col-9 mt-2">
            <h2>Owned Cars</h2>
        </div>
        <div class="col-3 mt-2"><button class="btn btn-success mx-auto" data-bs-toggle='modal'
                data-bs-target='#addCarModal'>Add Car</button></div>
    </div>
    <hr>
    <div class="row mx-0">
        <div class="col-9">
            <?php
            if (isset($_POST['id'])) {
                $stmt = $conn->prepare("SELECT * FROM `cardetails` WHERE `accountsId` = $userId");
                $stmt->execute();
                $result = $stmt->fetchAll();

                $counter = 0;
                foreach ($result as $row) {
                    $counter += 1;
                    $brandID = $row['carBrandID'];
                    $modelID = $row['carModelID'];
                    $stmt = $conn->prepare("SELECT * FROM `carbrands` WHERE `id` = $brandID");
                    $stmt->execute();
                    $resultBrand = $stmt->fetchAll();

                    $stmt = $conn->prepare("SELECT * FROM `carmodels` WHERE `id` = $modelID");
                    $stmt->execute();
                    $resultModel = $stmt->fetchAll();

                    echo "<div class='d-flex'><form class='mt-2' action='userscars.php' method='post'><input type='hidden' name='userId' value='" . $userId . "'><button class='btn text-primary' type='submit' name='selectCar' value='" . $row['referenceId'] . "'>Select</button></form><p class='my-3'>" . $counter . ". " . $resultBrand[0]['brandName'] . " " . $resultModel[0]['modelName'] . " : " . $row['plateNumber'] . "</p>" . '<input id="carName' . $row['referenceId'] . '" type="hidden" value="' . $resultBrand[0]['brandName'] . " " . $resultModel[0]['modelName'] . " : " . $row['plateNumber'] . '">' . "<hr></div>";
                }
            }
            ?>
        </div>
        <div class="col-3">
            <?php
            foreach ($result as $row) {
                if ($mainCar != $row['referenceId']) {
                    echo '<p><span><button class="btn btn-danger" id="delCarClick' . $row['referenceId'] . '"><i class="bi bi-trash-fill"></i></button><input id="carID' . $row['referenceId'] . '" type="hidden" value="' . $row['referenceId'] . '"></span></p><hr>';
                } else {
                    echo '<p><span><button class="btn btn-secondary" disabled><i class="bi bi-trash-fill"></i></button></span></p><hr>';
                }
            }
            ?>
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
                <form action="userscars.php" method="post">
                    <div class="form-group my-1">
                        <input type="hidden" name="mainId" value="<?php if (isset($_POST['id'])) {
                            echo $_POST['id'];
                        } ?>">
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
                        <label for="lName">Plate Number <sup>*</sup></label>
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
<div class="modal fade" id="delCarModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">
                    Are you sure you want to delete
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" disabled class="form-control text-center" id="delCarText" />
                <form action="userscars.php" method="post" class="text-center">
                    <div class="mb-3">
                        <input type="hidden" name="delCarID" id="delCarID" />
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
    <?php

    foreach ($result as $row) {
        echo "$('#delCarClick" . $row['referenceId'] . "').click(function () {";
        echo "$('#delCarID').val($('#carID" . $row['referenceId'] . "').val());";
        echo "$('#delCarText').val($('#carName" . $row['referenceId'] . "').val());";
        echo "$('#delCarModal').modal('toggle');});";

    }
    ?>
</script>