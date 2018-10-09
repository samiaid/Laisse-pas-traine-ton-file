<!doctype html>
<html lang="en">
<head>
    <title>Upload</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php
$uploadDir = 'files/';
if (isset($_POST['delete'])){
    if (file_exists($uploadDir.$_POST['image'])){
        unlink($uploadDir.$_POST['image']);
    }
}
if(isset($_POST['load']) && !empty($_POST)) {
    if (count($_FILES['upload']['name']) > 0) {
        for ($i = 0; $i < count($_FILES['upload']['name']); $i++) {
            $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
            $allowExtension = ['jpg', 'png', 'gif'];
            $maxFileSize = 1000000;
            if (isset($_POST['load']) && !empty($_POST)) {
                if (count($_FILES['upload']['name']) > 0) {
                    for ($i = 0; $i < count($_FILES['upload']['name']); $i++) {
                        $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
                        $extensionFile = pathinfo($_FILES['upload']['name'][$i], PATHINFO_EXTENSION);
                        if (!in_array($extensionFile, $allowExtension)) {
                            $error = 'Merci de choisir un fichier jpg, png ou gif';
                        } elseif (filesize($_FILES['upload']['tmp_name'][$i]) > $maxFileSize) {
                            $error = 'La taille du fichier ne doit pas exceder 1Mo';
                        } elseif (!isset($error)){
                            if ($tmpFilePath != ''){
                                $shortname = 'image' . uniqid() . '.' . $extensionFile;
                                $filepath = 'files/' . $shortname;
                                if (move_uploaded_file($tmpFilePath, $filepath)){
                                    $files[] = $filepath;
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
?>
<div class="container">

    <form method="post" enctype="multipart/form-data" action="#">
        <div class="form-group">
            <label for="upload">upload : </label>
            <input type="file" name="upload[]" id="upload" multiple="multiple">
        </div>
        <button type="submit" name="load" class="btn btn-primary">Send</button>
    </form>

    <div>
        <p class="bg-danger"><?php if (isset($error)) echo $error; ?></p>
    </div>

    <div class="row">
        <?php
        $listFiles = scandir('files/');
        foreach ($listFiles as $value){
            if ($value != '.' && $value != '..') { ?>
                <div class="col-sm-6 col-md-4">
                    <div class="thumbnail">
                        <img src="files/<?= $value; ?>" alt="image">
                        <div class="caption">
                            <h3><?= $value; ?></h3>
                            <form method="POST" action="#">
                                <input type="hidden" name="image" value="<?= $value; ?>">
                                <button type="submit" name="delete" class="btn btn-warning">delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
        ?>
    </div>
</div>




<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>
<?php