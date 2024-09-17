<label for="lName">Car Model <sup>*</sup></label>
<select class="form-select select-model" id="cModel" name="cModel">
    <?php
    include 'db.php';
    $brandID = $_POST['newID'];
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