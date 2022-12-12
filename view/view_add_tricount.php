<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Add Tricount</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
    <nav class="navbar navbar-light bg-light">
    <a class="navbar-brand" href="tricount/index">
        <button type="button" class="btn btn-primary">Cancel</button>
    </a>
   
    </nav>
        <div class="main">
            <form method='post' action='tricount/add_tricount' enctype='multipart/form-data'>
                Title : <br>
                <textarea name='title' id='title' rows='1'></textarea> <br>
                Descripton (optional) : <br>
                <textarea name='description' id='title' rows='3'></textarea> <br>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>

            <?php if (count($errors) != 0): ?>
                <div class='errors'>
                    <p>Please correct the following error(s) :</p>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>


        </div>
    </body>
</html>

