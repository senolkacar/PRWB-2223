<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Add Tricount</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
	integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    </head>
    <body>
        <div  class="container p-3 mb-3 text-dark" style="background-color: #E3F2FD;">
            <div class="d-flex justify-content-between mb-3">   
                <a class="navbar-brand" href="tricount/index">
                <button type="button" class="btn btn-outline-danger">Cancel</button>
                </a>
                <div class="h2">Tricount  &#32 <i class="bi bi-caret-right-fill"></i> &#32 Add </div>

                <div> <button type="submit" class="btn btn-primary"  form="form1">Save</button> </div>
            </div>
        </div>

        <div class="container-sm">
            <form method='post' action='tricount/add_tricount' enctype='multipart/form-data' id="form1">
                <div class="mb-3 mt-3">
                    <label for='title'> Title : </label>
                    <input type="text" class="form-control" name='title' id='title' value = "<?=$title;?>">
                 </div>

                 <?php if (count($errors_title) != 0): ?>
                    <div class='errors'>
                        <ul>
                            <?php foreach ($errors_title as $error_title): ?>
                                <li class="text-danger"><?= $error_title ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                 <div class="mb-3 mt-3">
                    <label for='description'> Descripton (optional) :  </label>
                    <textarea class="form-control" name='description' id='title' rows='3'><?=$description;?></textarea> <br>
                 </div>

                 <?php if (count($errors_description) != 0): ?>
                    <div class='errors'>
                        <ul>
                            <?php foreach ($errors_description as $error_description): ?>
                                <li class="text-danger"><?= $error_description ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>               
            </form>
        </div>
        <br>
    </body>
</html>

