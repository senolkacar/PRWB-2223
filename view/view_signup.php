<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tricount</title>
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
	integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>

<body>

    <header>
        <div class="container p-3 mb-3 text-dark" style="background-color: #0D6EFD;">       
        <h3 class="text-white">Tricount</h3>               
        </div>
    </header>
    <div class="container">
    <div class="container-sm border">
        <h2 class="text-center mb-3 mt-3">Sign up</h2>
            <form action="main/signup" method="post">
            <div class="input-group mt-3 border-top">
                <div class="input-group-prepend mt-3">
                    <span class="input-group-text">@</span>
                </div>
            <input type="email" class="form-control mt-3" placeholder="Email" name="mail" id="mail" value="<?=$mail?>">
            </div>
                    <?php if (count($errors_email) != 0): ?>
                    <div class='errors'>
                        <ul>
                            <?php foreach ($errors_email as $error): ?>
                                <text class="text-danger"><li><?= $error ?></li></text>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
            <div class="input-group mt-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span> 
                </div>
            <input type="text" class="form-control" name="full_name" placeholder="Full Name" id="full_name" value="<?=$full_name?>">
            </div>        
                <?php if (count($errors_full_name) != 0): ?>
                    <div class='errors'>
                        <ul>
                            <?php foreach ($errors_full_name as $error): ?>
                                <text class="text-danger"><li><?= $error ?></li></text>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
            <div class="input-group mt-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="bi bi-credit-card-2-front-fill"></i></span>
                </div>
            <input type="text" class="form-control" name="iban" placeholder="IBAN" id="iban" value="<?=$iban?>">
            </div>       
                <?php if (count($errors_iban) != 0): ?>
                    <div class='errors'>
                        <ul>
                            <?php foreach ($errors_iban as $error): ?>
                                <text class="text-danger"><li><?= $error ?></li></text>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
            <div class="input-group mt-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                </div>
            <input type="password" class="form-control" placeholder="Password" name="password" id="password" value="<?=$password?>">
            </div>      
                <?php if (count($errors_password) != 0): ?>
                    <div class='errors'>
                        <ul>
                            <?php foreach ($errors_password as $error): ?>
                                <text class="text-danger"><li><?= $error ?></li></text>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
            <div class="input-group mt-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                </div>
            <input type="password" class="form-control" placeholder="Confirm your password" name="password_confirm" id="password_confirm" value="<?=$password_confirm?>">
            </div>
                    <?php if (count($errors_password_confirm) != 0): ?>
                    <div class='errors'>
                        <ul>
                            <?php foreach ($errors_password_confirm as $error): ?>
                                <text class="text-danger"><li><?= $error ?></li></text>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    <input type="submit" class="btn btn-primary w-100 mt-3"  value="Sign Up">
            </form>
            <a href="main/index"> <button type="button" class="btn btn-outline-danger w-100 mt-2 mb-3">Cancel</button></a>
        </div>
    </div>
    </div>
</body>

</html>