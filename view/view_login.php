<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tricount</title>
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
</head>

<body>

    <header>
        <div class="container p-3 mb-3 text-dark" style="background-color: #0D6EFD;">
        <i class="fa-solid fa-cat"></i>            
        <h3 class="text-white">Tricount</h3>               
        </div>
    </header>


    <div class="login-form">
        <form action="main/login" method="post">
            <h2 class="text-center">Sign In</h2>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <span class="fa fa-user"></span>
                        </span>                    
                    </div>
                    <input type="email" class="form-control" name="mail" id="mail" placeholder="Username" required="required" value="<?=$mail; ?>">

                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fa fa-lock"></i>
                        </span>                    
                    </div>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password" required="required" value="<?=$password; ?>">

                    

                </div>
            </div>        
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">Log in</button>
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

            <p class="text-center">
                <a href="main/signup" class="float-center">New here ? Click here to join the party <i class="fa fa-rocket"></i> !</a>
            </p>
        </form>
  
                        
</body>
</html>
