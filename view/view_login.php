<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tricount</title>
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="lib/just-validate-4.2.0.production.min.js" type="text/javascript"></script>
    <script src="lib/jquery-3.6.4.min.js" type="text/javascript"></script>
    <script>
        <?php if ($justvalidate) : ?>
            let emailExists = false;

            function debounce(fn, time) {
                var timer;
                return function() {
                    clearTimeout(timer);
                    timer = setTimeout(() => {
                        fn.apply(this, arguments);
                    }, time);
                }
            }

            $(function() {
                const validation = new JustValidate('#loginform', {
                    validateBeforeSubmitting: true,
                    lockForm: false,
                    focusInvalidField: false,
                    errorFieldCssClass: 'is-invalid',
                    successFieldCssClass: 'is-valid',
                    successLabelCssClass: 'valid-feedback',
                    errorLabelCssClass: 'invalid-feedback',
                });
            
                validation
                    .addField('#mail',
                        [{
                                rule: 'required',
                                errorMessage: 'Mail is required'
                            },
                            {
                                rule: 'email',
                                errorMessage: 'Mail is not valid'
                            },
                        ], {
                            successMessage: "Looks good!",
                        })
                    .addField('#password', [{
                            rule: 'required',
                            errorMessage: 'Password is required'
                        },
                        {
                            rule: 'minLength',
                            value: 8,
                            errorMessage: 'Password must be at least 8 characters'
                        },
                        {
                            rule: 'maxLength',
                            value: 16,
                            errorMessage: 'Password must be at most 16 characters'
                        },
                        {
                            rule: 'customRegexp',
                            value: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,16}$/,
                            errorMessage: 'Password must contain at least one uppercase letter, one lowercase letter, one digit and one special character',
                        }
                    ], {
                        successMessage: "Looks good!",
                    })
                    .onValidate(debounce(async function(event){
                        if($("#mail").hasClass("form-control is-valid")){
                            emailExists = await $.post("main/email_exists_service/", {
                            'mail': $("#mail").val()
                        }).then(function(data) {
                            return (data.trim() === "true");
                        });

                        if (!emailExists) {
                            this.showErrors({
                                '#mail': 'Mail does not exist for this user please sign up'
                            });
                        }
                        }

                    }, 300))
                    .onSuccess(function(event) {   
                        if (!emailExists) {
                    this.showErrors({
                        '#mail': 'Mail does not exist for this user please sign up'
                    });
                }else{
                    event.target.submit();
                }
});
                $("input:text:first").focus;
            });

        <?php endif; ?>
    </script>
</head>

<body>

    <header>
        <div class="container p-3 mb-3 text-dark" style="background-color: #0D6EFD;">
            <h3 class="text-white"><i class="fa-solid fa-cat me-3"></i>Tricount</h3>
        </div>
    </header>

    <div class="container d-flex align-items-center min-vh-100">
        <div class="container-sm border rounded">
            <form id="loginform" action="main/login" method="post">
                <h2 class="text-center mt-3 pb-3 border-bottom">Sign In</h2>
                <div class="mt-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-at"></i></span>
                    <input type="email" class="form-control <?php echo count($errors) != 0 ? 'is-invalid' : '' ?>" placeholder="Email" name="mail" id="mail" value="<?= $mail ?>">
                </div>
                <div class="mt-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" class="form-control <?php echo count($errors) != 0 ? 'is-invalid' : '' ?>" placeholder="Password" name="password" id="password" value="<?= $password ?>">
                </div>
                </div>
                <div class="form-group mt-3 mb-3">
                    <button type="submit" class="btn btn-primary btn-block w-100">Log in</button>
                </div>

                <div class="container-sm">
                    <?php if (count($errors) != 0) : ?>
                        <div class='errors'>
                            <ul>
                                <?php foreach ($errors as $error) : ?>
                                    <li class="text-danger"><?= $error; ?></li>
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