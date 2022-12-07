<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tricount</title>
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css" type="text/css">
</head>

<body>
    <div class="title">Sign in</div>
    <div class="main">
        <form action="main/login" method="post">
        <table>   
        <tr>
                <td><label for="mail">Email</label></td>
                <td><input type="email" name="mail" id="mail" required></td>
        </tr>
        <tr>
                <td><label for="password">Password</label></td>
                <td><input type="password" name="password" id="password" required></td>
        </tr>       
            </table> 
                <input type="submit" value="Login">
        </form>
        <?php if (count($errors)!=0) : ?>
            <div class='errors'>
                <p>Please correct the following error(s):</p>
                <ul>
                    <?php foreach ($errors as $error) : ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
    <div class="main">
        <div class="input">
            <a href="main/signup">Sign up</a>
    </div>
</body>

</html>