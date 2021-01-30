<?php
    include '../layout/header.php';
?>
    <h3>Upload File</h3>

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


    <?php if(isset($_REQUEST['message'])) {
        $message = urldecode($_REQUEST['message']);
        ?>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-success">
                    <?php echo $message?>
                </div>
            </div>
        </div>
    <?php } ?>

    <br/>
    <form action="store.php" method="post" enctype="multipart/form-data">
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

        <div class="row">
            <div class="col-12">
                <label for="file">File</label>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="file-upload-wrapper">
                    <input type="file" id="file" name="file" class="file-upload form-control" data-max-file-size="1M" />
                </div>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-12">
                <input type="submit" value="Upload" class="btn btn-sm btn-primary">
            </div>
        </div>
    </form>
<?php
    include '../layout/footer.php';
?>
