<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Edit Tricount</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
    <nav class="navbar navbar-light bg-light">
    <a class="navbar-brand" href="tricount/index">
        <button type="button" class="btn btn-primary">Back</button>
    </a>
    <p class="text-right"><?=$tricount->title?> &#32 &#9654; &#32 Edit</p>
    </nav>
        <div class="main">
            <form method='post' action='tricount/edit_tricount/$id' enctype='multipart/form-data'>
                <p>Settings</p>
                Title: <br>
                <textarea name='title'  id='title'  rows='1'><?= $tricount->title; ?></textarea> <br>
                Description (optional) : <br>
                <textarea name='description' id='description'  rows='2'><?= $tricount->description; ?></textarea> <br>
                <p>Subscriptions</p>
                <input type='submit' value='Save'>
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