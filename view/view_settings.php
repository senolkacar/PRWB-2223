<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>your tricounts</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
      
    </head>
    <body>
    <nav class="navbar navbar-light bg-light">
    <a class="navbar-brand" href="tricount/index">
        <button type="button" class="btn btn-primary">Back</button>
    </a>
    <p class="text-right">Settings</p>
    </nav>
    <p>Hey <b><?= $user->full_name ?></b></p>
    <p>I know your email address is <?= $user->mail ?>.</p>
    <p>What can i do for you?</p>
    <footer>
        <div class="btn-group-vertical" role="group">
        <a href="user/edit_profile"><button type="button" class="btn btn-secondary">Edit profile</button></a>
        <a href="user/edit_password"><button type="button" class="btn btn-secondary">Change password</button></a>
        <a href="main/logout"><button type="button" class="btn btn-secondary">logout</button></a>
        </div>
    </footer>
    </body>
</html>