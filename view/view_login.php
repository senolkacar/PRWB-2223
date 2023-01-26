<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tricount</title>
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
	integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>

<body>

    <header>
        <div class="container p-3 mb-3 text-dark" style="background-color: #0D6EFD;">
        <h3 class="text-white"><i class="fa-solid fa-cat me-3"></i>Tricount</h3>               
        </div>
    </header>

    <div class="container d-flex align-items-center min-vh-100">
    <div class="container-sm border rounded">
        <form action="main/login" method="post">
            <h2 class="text-center mt-3 pb-3 border-bottom">Sign In</h2>
            <div class="input-group mt-3">
                <span class="input-group-text"><i class="fa-solid fa-at"></i></span>
                <input type="email" class="form-control <?php echo count($errors)!=0 ? 'is-invalid' : ''?>" placeholder="Email" name="mail" id="mail" value="<?=$mail?>">
            </div>
            <div class="input-group mt-3">
                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                <input type="password" class="form-control <?php echo count($errors)!=0 ? 'is-invalid' : ''?>" placeholder="Password" name="password" id="password" value="<?=$password?>">
            </div>          
            <div class="form-group mt-3 mb-3">
                <button type="submit" class="btn btn-primary btn-block w-100">Log in</button>
            </div>

            <div class ="container-sm">
            <?php if (count($errors) != 0): ?>
                        <div class='errors'>
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li class="text-danger"><?= $error;?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
            <?php endif; ?>
            </div>

            <div class="text-center mb-3">
                <a href="main/signup" class="float-center text-decoration-none">New here ? Click here to join the party <i class="fa-solid fa-heart"></i> !</a>
            </div>
        </form>
    </div>
    </div>             
</body>
</html>
