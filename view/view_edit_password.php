<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Tricount</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
    <nav class="navbar navbar-light bg-light">
    <a class="navbar-brand" href="user/settings">
        <button type="button" class="btn btn-primary">Back</button>
    </a>
    <p class="text-right">Change password</p>
    </nav>
        <div class="main">
            <form method='post' action='user/edit_password' enctype='multipart/form-data'>
                <p>Change your password</p>

                <input type="password" class="form-control" name="old_password" id="old_password" placeholder="old password" required="required">
                <input type="password" class="form-control" name="new_password" id="new_password" placeholder="New password" required="required">
                <input type="password" class="form-control" name="new_password_confirm" id="new_password_confirm" placeholder="New password confirm" required="required">
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

