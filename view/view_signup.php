<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tricount</title>
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>

<body>

    <header>
        <div  class="container p-3 mb-3 text-dark" style="background-color: #0D6EFD;">       
        <h3 class="text-white">Tricount</h1>               
        </div>
    </header>

    <div class="container-sm">
        <div class="title">Sign up</div>
        <div class="main">
            <form action="main/signup" method="post">
            <table>   
            <tr>
                    <td><label for="mail">Email</label></td>
                    <td><input type="email" name="mail" id="mail" value="<?=$mail?>"></td>
                    <td><?php if (count($errors_email) != 0): ?>
                    <div class='errors'>
                        <ul>
                            <?php foreach ($errors_email as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?></td>
            </tr>
            <tr>
                    <td><label for="fullname">Full Name</label></td>
                    <td><input type="text" name="full_name" id="full_name" value="<?=$full_name?>"></td>
                    <?php if (count($errors_full_name) != 0): ?>
                    <td><div class='errors'>
                        <ul>
                            <?php foreach ($errors_full_name as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?></td>
            </tr>   
            <tr>
                    <td><label for="iban">IBAN</label></td>
                    <td><input type="text" name="iban" id="iban" value="<?=$iban?>"></td>
                    <?php if (count($errors_iban) != 0): ?>
                    <td><div class='errors'>
                        <ul>
                            <?php foreach ($errors_iban as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?></td>
            </tr>    
            <tr>
                    <td><label for="password">Password</label></td>
                    <td><input type="password" name="password" id="password" value="<?=$password?>"></td>
                    <?php if (count($errors_password) != 0): ?>
                    <td><div class='errors'>
                        <ul>
                            <?php foreach ($errors_password as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?></td>
            </tr>    
            <tr>
                    <td><label for="password_confirm">Confirm Password</label></td>
                    <td><input type="password" name="password_confirm" id="password_confirm" value="<?=$password_confirm?>"></td>
                    <?php if (count($errors_password_confirm) != 0): ?>
                    <td><div class='errors'>
                        <ul>
                            <?php foreach ($errors_password_confirm as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?></td>
            </tr>        
                </table> 
                    <input type="submit" value="Sign Up">
            </form>
            <a href="main/index"><button type="cancel">Cancel</button></a>
        </div>
    </div>
</body>

</html>