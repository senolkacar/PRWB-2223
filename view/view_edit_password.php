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
        <script src="lib/just-validate-4.2.0.production.min.js" ></script>
        <script src="lib/jquery-3.6.4.min.js" ></script>
        <script src="lib/sweetalert2@11.js" ></script>
        <script>
            <?php if($justvalidate):?>
                let oldPasswordValid = false;
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
                const validation = new JustValidate('#changepw', {
                    validateBeforeSubmitting: true,
                    lockForm: false,
                    focusInvalidField: false,
                    errorFieldCssClass: 'is-invalid',
                    successFieldCssClass: 'is-valid',
                    successLabelCssClass: 'text-success',
                    errorLabelCssClass: 'text-danger',
                });
                validation
                .addField('#old_password',[{
                    rule:'required',
                    errorMessage: 'Please enter your old password'
                },
                ],{
                    successMessage: "Looks good!",
                }).addField('#new_password', [{
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
                        },
                        {
                            validator: function(value, fields) {
                                if (fields['#old_password'] && fields['#old_password'].elem) {
                                    const repeatPasswordValue = fields['#old_password'].elem.value;
                                    return value !== repeatPasswordValue;
                                }
                                return false;
                            },
                            errorMessage: 'Password must be different from old password',
                        }
                    ], {
                        successMessage: "Looks good!",
                    })
                    .addField('#new_password_confirm', [{
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
                                if (fields['#new_password'] && fields['#new_password'].elem) {
                                    const repeatPasswordValue = fields['#new_password'].elem.value;
                                    return value === repeatPasswordValue;
                                }
                                return true;
                            },
                            errorMessage: 'Password confirmation must be the same as password',
                        },
                    ], {
                        successMessage: "Looks good!",
                    }).onValidate(debounce(async function(event) {
                        oldPasswordValid = await $.post('user/old_password_valid_service', {
                            old_password: $('#old_password').val()
                        }).then(function(data) {
                                return (data.trim() === "true");
                        });

                        if(!oldPasswordValid){
                            this.showErrors({
                                    '#old_password': 'Old password does not match'
                                });
                        }
                    }, 300))
                    .onSuccess(function(event) {
                        if(oldPasswordValid){
                            $("#changepw").submit();
                        }
                    });
                $("input:text:first").focus;
            });
            <?php endif;?>
            let formChanged = false;
            $(function() {
            $('input').on('input', function() {
                formChanged = true;
            });
            $('#back-button').on('click', function(e) {                    
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


        <div  class="container p-3 mb-3 text-dark" style="background-color: #E3F2FD;">
            <div class="d-flex justify-content-between mb-3">  
                <a class="btn btn-outline-danger" id="back-button" href="user/settings">Back</a>
                <div class="text-secondary fw-bold mt-2" >Change Password</div>
                <div class="h2"> </div>
            </div>
        </div>

    
        <div class="container-sm">
            <form method='post' id="changepw" action='user/edit_password' enctype='multipart/form-data'>
            <div class="h2">Change your password</div>
                <div class="mb-3 mt-3">
                    <label for='old_password'> Old Password : </label>
                    <input type="password" class="form-control" name="old_password" id="old_password"  required="required" value="<?=$old_password?>" >
                </div>
                <div class="mb-3 mt-3">
                    <label for='new_password'> New Password : </label>
                    <input type="password" class="form-control" name="new_password" id="new_password"  required="required" value="<?=$new_password?>">
                </div>
                <div class="mb-3 mt-3">
                    <label for='new_password_confirm'> New Password Confirm : </label>
                    <input type="password" class="form-control" name="new_password_confirm" id="new_password_confirm"  required="required" value="<?=$new_password_confirm?>">
                </div>
                <input type='submit' class="btn btn-primary" value='Save Profile'>
            </form>

            <br>
            <?php if (count($errors) != 0): ?>
                <div class="text-danger">
                    <p>Please correct the following error(s) :</p>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php elseif (strlen($success) != 0): ?>
                <p ><span class='text-success'><?= $success ?></span></p>
            <?php endif; ?>


        </div>
    </body>
</html>

