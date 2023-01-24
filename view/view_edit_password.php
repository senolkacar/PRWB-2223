<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Tricount</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
	integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    </head>
    <body>


        <div  class="container p-3 mb-3 text-dark" style="background-color: #E3F2FD;">
            <div class="d-flex justify-content-between mb-3">  
                <a class="navbar-brand" href="user/settings">
                <button type="button" class="btn btn-outline-danger">Back</button>
                </a>
                <div class="text-secondary fw-bold mt-2" >Change Password</div>
                <div class="h2"> </div>
            </div>
        </div>

    
        <div class="container-sm">
            <form method='post' action='user/edit_password' enctype='multipart/form-data'>
            <div class="h2">Change your password</div>
                <div class="mb-3 mt-3">
                    <label for='old_password'> Old Password : </label>
                    <input type="password" class="form-control" name="old_password" id="old_password"  required="required" value="<?=$old_password?>" >
                </div>
                <div class="mb-3 mt-3">
                    <label for='new_password'> New Password : </label>
                    <input type="password" class="form-control" name="new_password" id="new_password"  required="required" value="<?=$new_password?>">
                </div>
                <div class="mb-3 mt-3">
                    <label for='new_password_confirm'> New Password Confirm : </label>
                    <input type="password" class="form-control" name="new_password_confirm" id="new_password_confirm"  required="required" value="<?=$new_password_confirm?>">
                </div>
                <input type='submit' class="btn btn-primary" value='Save Profile'>
            </form>

            <br>
            <?php if (count($errors) != 0): ?>
                <div class="text-danger">
                    <p>Please correct the following error(s) :</p>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php elseif (strlen($success) != 0): ?>
                <p ><span class='text-success'><?= $success ?></span></p>
            <?php endif; ?>


        </div>
    </body>
</html>

