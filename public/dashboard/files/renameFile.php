<?php

include '../layout/header.php';
?>
<h3>Rename File</h3>

<?php if(isset($_REQUEST['errors'])) {
    $errors = json_decode(urldecode($_REQUEST['errors']));
    ?>
    <div class="row">
        <div class="col-12">
            <div class="alert alert-danger">
                <?php foreach ($errors as $error){
                    echo $error;
                    echo "<br/>";
                } ?>
            </div>
        </div>
    </div>
<?php } ?>

<br/>
<form action="rename.php" method="post" enctype="multipart/form-data">

    <input hidden value="<?php echo urldecode($_GET['file']); ?>" name="path">
    <div class="row">
        <div class="col-12">
            <label for="name">Name</label>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="file-upload-wrapper">
                <input type="text" id="name" name="name" class="form-control" />
            </div>
        </div>
    </div>
    <br/>

    <br/>
    <div class="row">
        <div class="col-12">
            <input type="submit" value="Rename" class="btn btn-sm btn-primary">
        </div>
    </div>
</form>
<?php
include '../layout/footer.php';
?>
