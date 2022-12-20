<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Delete Tricount</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
    <nav class="navbar navbar-light bg-light">
    <a class="navbar-brand" href="tricount/edit_tricount/<?=$tricount->id;?>">
        <button type="button" class="btn btn-primary">Cancle</button>
    </a>
    </nav>
    <p > Are you sure?</p>
        <div class="main">
        Do you really want to delete tricount "<?=$tricount->title;?>"<br>
        and all of its dependencies?<br>
        <br>
        This process cannot be undone.
        <br><br>
         <form class='link' action='tricount/delete/<?=$tricount->id; ?>' method='post' >
            <input type='hidden' name='id_tricount' value='<?=$tricount->id;?>' >
            <button type="submit" class="btn btn-primary">Delete</button>
        </form>        
        </div>
        
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

    </body>
</html>