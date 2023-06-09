<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tricount</title>
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <script src="lib/just-validate-4.2.0.production.min.js" ></script>
    <script src="lib/jquery-3.6.4.min.js" ></script>
    <script src="lib/sweetalert2@11.js"></script>
    <script>
        <?php if ($justvalidate) : ?>
            let emailExists = false;
            let fNameExists = false;

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
                const validation = new JustValidate('#signupform', {
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
                    .addField('#full_name', [{
                            rule: 'required',
                            errorMessage: 'Full name is required'
                        },
                        {
                            rule: 'minLength',
                            value: 3,
                            errorMessage: 'Full name must be at least 3 characters'
                        },
                    ], {
                        successMessage: "Looks good!",
                    })
                    .addField('#iban', [{
                        rule: 'customRegexp',
                        value: /^$|^([a-zA-Z]{2}[0-9]{2}(?:[\s-]?[0-9]{4}){3})$/,
                        errorMessage: 'IBAN is not valid'
                    }, ], {
                        successMessage: 'Looks good!'
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
                    .addField('#password_confirm', [{
                            rule: 'required',
                            errorMessage: 'Password confirmation is required'
                        },
                        {
                            rule: 'minLength',
                            value: 8,
                            errorMessage: 'Password confirmation must be at least 8 characters'
                        },
                        {
                            rule: 'maxLength',
                            value: 16,
                            errorMessage: 'Password confirmation must be at most 16 characters'
                        },
                        {
                            rule: 'customRegexp',
                            value: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,16}$/,
                            errorMessage: 'Password confirmation must contain at least one uppercase letter, one lowercase letter, one digit and one special character',
                        },
                        {
                            validator: function(value, fields) {
                                if (fields['#password'] && fields['#password'].elem) {
                                    const repeatPasswordValue = fields['#password'].elem.value;
                                    return value === repeatPasswordValue;
                                }
                                return true;
                            },
                            errorMessage: 'Password confirmation must be the same as password',
                        },
                    ], {
                        successMessage: "Looks good!",
                    }).onValidate(debounce(async function(event) {
                            emailExists = await $.post("main/email_exists_service/", {
                                'mail': $("#mail").val()
                            }).then(function(data) {
                                return (data.trim() === "true");
                            });

                            if (emailExists) {
                                this.showErrors({
                                    '#mail': 'Mail already exists for this user please sign in or use another mail'
                                });
                            }
                            fnameExists = await $.post("main/fname_exists_service/", {
                                'full_name': $("#full_name").val()
                            }).then(function(data) {
                                return (data.trim() === "true");
                            });

                            if (fnameExists) {
                                this.showErrors({
                                    '#full_name': 'Full name already exists for this user please sign in or use another full name'
                                });
                            }

                    }, 300))
                    .onSuccess(function(event) {
                        if(!emailExists && !fnameExists){
                            $("#signupform").submit();
                        }
                    });
                $("input:text:first").focus;
            });

        <?php endif; ?>

        let formChanged = false; 
        $(function(){
            $('input').on('change', function() {
                    formChanged = true;
            });

            $('#cancle_btn').on('click', function(e) {                    
                    if (formChanged) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Unsaved changes !',
                            text: 'Are you sure you want to leave this form ? Changes you made will not be saved.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Leave Page',
                            cancelButtonText: 'Cancel',
                            confirmButtonColor: '#d33',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = $(this).attr('href');
                            }
                        });
                    }
                });

        });

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
            <form id="signupform" action="main/signup" method="post">
                <h2 class="text-center mb-3 mt-3 pb-3 border-bottom">Sign up</h2>
                <div class="mt-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-at"></i></span>
                    <input type="email" class="form-control <?php echo count($errors_email) != 0 ? 'is-invalid' : '' ?>" placeholder="Email" name="mail" id="mail" value="<?= $mail ?>">
                </div>
                </div>
                <?php if (count($errors_email) != 0) : ?>
                    <div class='errors'>
                        <ul>
                            <?php foreach ($errors_email as $error) : ?>
                                <li class="text-danger"><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <div class="mt-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                    <input type="text" class="form-control <?php echo count($errors_full_name) != 0 ? 'is-invalid' : '' ?>" name="full_name" placeholder="Full Name" id="full_name" value="<?= $full_name ?>">
                </div>
                </div>
                <?php if (count($errors_full_name) != 0) : ?>
                    <div class='errors'>
                        <ul>
                            <?php foreach ($errors_full_name as $error) : ?>
                                <li class="text-danger"><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <div class="mt-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-credit-card"></i></span>
                    <input type="text" class="form-control <?php echo count($errors_iban) != 0 ? 'is-invalid' : '' ?>" name="iban" placeholder="IBAN" id="iban" value="<?= $iban ?>">
                </div>
                </div>
                <?php if (count($errors_iban) != 0) : ?>
                    <div class='errors'>
                        <ul>
                            <?php foreach ($errors_iban as $error) : ?>
                                <li class="text-danger"><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <div class="mt-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" class="form-control <?php echo count($errors_password) != 0 ? 'is-invalid' : '' ?>" placeholder="Password" name="password" id="password" value="<?= $password ?>">
                </div>
                </div>
                <?php if (count($errors_password) != 0) : ?>
                    <div class='errors'>
                        <ul>
                            <?php foreach ($errors_password as $error) : ?>
                                <li class="text-danger"><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <div class="mt-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" class="form-control <?php echo count($errors_password_confirm) != 0 ? 'is-invalid' : '' ?>" placeholder="Confirm your password" name="password_confirm" id="password_confirm" value="<?= $password_confirm ?>">
                </div>
                </div>
                <?php if (count($errors_password_confirm) != 0) : ?>
                    <div class='errors'>
                        <ul>
                            <?php foreach ($errors_password_confirm as $error) : ?>
                                <li class="text-danger"><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <input type="submit" class="btn btn-primary w-100 mt-3" value="Sign Up">
            </form>
            <a href="main/index" id='cancle_btn' class="btn btn-outline-danger w-100 mt-2 mb-3">Cancel</a>
        </div>
    </div>
</body>

</html>