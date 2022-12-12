<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Tricount</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
    <nav class="navbar navbar-light bg-light">
    <a class="navbar-brand" href="user/settings">
        <button type="button" class="btn btn-primary">Back</button>
    </a>
    <p class="text-right">Edit Profile</p>
    </nav>
        <div class="main">
            <form method='post' action='user/edit_profile' enctype='multipart/form-data'>
                <p>Edit your profile</p>
                Mail : <br>
                <textarea name='mail' cols='30' rows='1'><?= $user->mail ?></textarea> <br>
                Full name : <br>
                <textarea name='full_name' cols='30' rows='1'><?= $user->full_name ?></textarea> <br>
                Iban : <br>
                <textarea name='iban' cols='30' rows='1'><?= $user->iban?></textarea> <br>
                <input type='submit' value='Save Profile'>
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
            <?php elseif (strlen($success) != 0): ?>
                <p><span class='success'><?= $success ?></span></p>
            <?php endif; ?>


        </div>
    </body>
</html>

