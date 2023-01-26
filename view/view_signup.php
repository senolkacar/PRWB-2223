<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tricount</title>
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
	integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>

<body>

    <header>
        <div class="container p-3 mb-3 text-dark" style="background-color: #0D6EFD;">       
        <h3 class="text-white"><i class="fa-solid fa-cat me-3"></i>Tricount</h3>               
        </div>
    </header>
    <div class="container d-flex align-items-center min-vh-100">
    <div class="container-sm border">
    <form action="main/signup" method="post">
        <h2 class="text-center mb-3 mt-3 pb-3 border-bottom">Sign up</h2>
            <div class="input-group mt-3">
                <span class="input-group-text"><i class="fa-solid fa-at"></i></span>
                <input type="email" class="form-control <?php echo count($errors_email)!=0 ? 'is-invalid' : ''?>" placeholder="Email" name="mail" id="mail" value="<?=$mail?>">
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
                <span class="input-group-text"><i class="fa-solid fa-user"></i></span> 
                <input type="text" class="form-control <?php echo count($errors_full_name)!=0 ? 'is-invalid' : ''?>" name="full_name" placeholder="Full Name" id="full_name" value="<?=$full_name?>">
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
                <span class="input-group-text"><i class="fa-solid fa-credit-card"></i></span>
                <input type="text" class="form-control <?php echo count($errors_iban)!=0 ? 'is-invalid' : ''?>" name="iban" placeholder="IBAN" id="iban" value="<?=$iban?>">
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
                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                <input type="password" class="form-control <?php echo count($errors_password)!=0 ? 'is-invalid' : ''?>" placeholder="Password" name="password" id="password" value="<?=$password?>">
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
                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                <input type="password" class="form-control <?php echo count($errors_password_confirm)!=0 ? 'is-invalid' : ''?>" placeholder="Confirm your password" name="password_confirm" id="password_confirm" value="<?=$password_confirm?>">
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
            <a href="main/index" class="btn btn-outline-danger w-100 mt-2 mb-3">Cancel</a>
        </div>
    </div>
</body>

</html>