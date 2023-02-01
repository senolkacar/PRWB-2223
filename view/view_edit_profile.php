<!DOCTYPE html>
<html lang="en">
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
                <a href="user/settings" class="btn btn-outline-danger"> Back  </a>
                <div class="text-secondary fw-bold mt-2" >Edit Profile</div>
                <div class="h2"> </div>
            </div>
        </div>


        <div class="container-sm">
            <form method='post' action='user/edit_profile' enctype='multipart/form-data'>
                <div class="h2">Edit your profile</div>
                <div class="mb-3 mt-3">
                    <label for='mail'> Mail : </label>
                    <input class="form-control" name='mail' id='mail' cols='30' rows='1'><?= $mail ?></input>
                </div>

                    <?php if (count($errors_mail) != 0): ?>
                    <div class="text-danger">
                        <ul>
                            <?php foreach ($errors_mail as $error_mail): ?>
                                <li><?= $error_mail ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                     <?php endif; ?>

                <div class="mb-3 mt-3">
                    <label for='full_name'> Full name : </label>
                    <input class="form-control" name='full_name' id='full_name' cols='30' rows='1'><?= $full_name ?></input>
                </div>

                    <?php if (count($errors_name) != 0): ?>
                        <div class="text-danger">
                            <ul>
                                <?php foreach ($errors_name as $error_name): ?>
                                    <li><?= $error_name ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>


                <div class="mb-3 mt-3">
                    <label for='iban'> Iban : </label>
                    <input class="form-control" name='iban' id='iban' cols='30' rows='1'><?= $iban?></input>
                </div>

                    <?php if (count($errors_iban) != 0): ?>
                        <div class="text-danger">
                            <ul>
                                <?php foreach ($errors_iban as $error_iban): ?>
                                    <li><?= $error_iban ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                

                <input type='submit' class="btn btn-primary" value='Save Profile'>
            </form>
            <br>
            <?php if (count($errors) == 0 && strlen($success) != 0 ): ?>
                <p><span class='text-success'><?= $success ?></span></p>
            <?php endif; ?>


        </div>
    </body>
</html>

