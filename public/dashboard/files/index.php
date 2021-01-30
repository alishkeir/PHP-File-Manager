<?php
require_once realpath('./../../../vendor/autoload.php');
require_once realpath('../../assets/constants.php');

use App\Models\File;

include '../layout/header.php';

$page = 1;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

$model = new File();
$stmt = $model->index($page, LIMIT);

$totalPages = $model->getTotalPages(LIMIT);

?>

<div class="row">
    <div class="col-12">
        <h3>
            My Files
            <a href="create.php" class="btn btn-primary">
                Upload
            </a>
        </h3>
    </div>
</div>

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

<div class="row">
    <div class="col-12">
        <table class="table table-striped table-bordered">
            <tr>
                <th>Name</th>
                <th>Format</th>
                <th>Size</th>
                <th>Download Link</th>
                <th style="width: 15%">Actions</th>
            </tr>

            <?php while ($file = $stmt->fetchObject()) { ?>
                <tr>
                    <td><?php echo $file->name ?></td>
                    <td><?php echo $file->format ?></td>
                    <td><?php echo number_format($file->size/1000, 2, '.', ',') ?> KB</td>
                    <td><a href="download.php?file=<?php echo urlencode($file->path); ?>">Download</a></td>
                    <td>
                        <a href="renameFile.php?file=<?php echo urlencode($file->path); ?>" class="btn btn-warning">
                            <i class="fa fa-edit"></i>
                        </a>

                        <a href="delete.php?file=<?php echo urlencode($file->path);?>" class="btn btn-danger">
                            <i class="fa fa-times"></i>
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </table>

        <ul class="pagination" style="float: right">
            <li><a href="?page=1">First</a></li>
            <li class="<?php if($page <= 1){ echo 'disabled'; } ?>">
                <a href="<?php if($page <= 1){ echo '#'; } else { echo '?page=' .($page - 1); } ?>">Prev</a>
            </li>
            <li class="<?php if($page >= $totalPages){ echo 'disabled'; } ?>">
                <a href="<?php if($page >= $totalPages){ echo '#'; } else { echo '?page=' .($page + 1); } ?>">Next</a>
            </li>
            <li><a href="?page=<?php echo $totalPages; ?>">Last</a></li>
        </ul>
    </div>
</div>

<?php

include '../layout/footer.php';

?>