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
    <div class="title">Sign up</div>
    <div class="main">
        <form action="main/signup" method="post">
        <table>   
        <tr>
                <td><label for="mail">Email</label></td>
                <td><input type="email" name="mail" id="mail" required></td>
        </tr>
        <tr>
                <td><label for="fullname">Full Name</label></td>
                <td><input type="text" name="fullname" id="fullname" required></td>
        </tr>   
        <tr>
                <td><label for="iban">IBAN</label></td>
                <td><input type="text" name="iban" id="iban" required></td>
        </tr>    
        <tr>
                <td><label for="password">Password</label></td>
                <td><input type="password" name="password" id="password" required></td>
        </tr>    
        <tr>
                <td><label for="confirmpassword">Confirm Password</label></td>
                <td><input type="password" name="confirmpassword" id="confirmpassword" required></td>
        </tr>        
            </table> 
                <input type="submit" value="Sign Up">
                <!--not redirection to login page because of required fields-->
                <a href="main/index"><button type="cancel">Cancel</button></a>
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
</body>

</html>