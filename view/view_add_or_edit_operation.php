<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= $page_title ?></title>
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <script src="lib/jquery-3.6.4.min.js"></script>
    <script src="lib/just-validate-4.2.0.production.min.js"></script>
    <script src="lib/sweetalert2@11.js"></script>
    <script src="lib/just-validate-plugin-date-1.2.0.production.min.js"></script>
    <script>
        let totalAmount, totalWeight;
        let formChanged = false;

        <?php if ($justvalidate) : ?>
            $(function() {
                const validation = new JustValidate('#form1', {
                    validateBeforeSubmitting: true,
                    lockForm: true,
                    focusInvalidField: false,
                    errorFieldCssClass: 'is-invalid',
                    successFieldCssClass: 'is-valid',
                    successLabelCssClass: 'valid-feedback',
                    errorLabelCssClass: 'invalid-feedback',
                });

                validation
                    .addField('#title', [{
                            rule: 'required',
                            errorMessage: 'Title is required'
                        },
                        {
                            rule: 'minLength',
                            value: 3,
                            errorMessage: 'Title must be at least 3 characters'
                        },
                    ], {
                        successMessage: "Looks good!",
                    })
                    .addField('#amount-total', [{
                            rule: 'required',
                            errorMessage: 'Total amount is required'
                        },
                        {
                            rule: 'minNumber',
                            value: 0.01,
                            errorMessage: 'Amount must be greater than or equal to 1 cent'
                        },
                    ], {
                        successMessage: "Looks good!",
                    })
                    .addField('#date', [{
                            rule: 'required',
                            errorMessage: 'Date is required'
                        },
                        {
                            plugin: JustValidatePluginDate((fields) => {
                                return {
                                    isBefore: new Date(),
                                };
                            }),
                            errorMessage: 'Date cannot be in the future',
                        },
                    ], {
                        successMessage: "Looks good!",
                    })
                    .addRequiredGroup('#errors-container',
                        'Select atleast one participant'
                        )
                    .onSuccess(function() {
                        formChanged = false;
                        $('#form1').submit();
                    });

                $('input[name="weights[]"]').on('input change', function() {
                    setTimeout(function() {
                        validation.revalidateGroup('#errors-container').then(isValid => {
                            if(isValid){
                                <?php foreach ($subscriptions as $subscription) : ?>
                                    $('#checkbox<?= $subscription->id ?>').removeClass('is-invalid');
                                    $('#checkbox<?= $subscription->id ?>').removeAttr('style');
                                    $('#subscription-amount<?= $subscription->id ?>').removeClass('is-invalid');
                                    $('#subscription-amount<?= $subscription->id ?>').removeAttr('style');
                                    $('#weights<?= $subscription->id ?>').removeClass('is-invalid');
                                    $('#weights<?= $subscription->id ?>').removeAttr('style');
                                <?php endforeach; ?>
                            }
                        })
                    }, 100);
                });

            });


        <?php endif; ?>

        $(function() {

            $('form').submit(function() {
                $('input[name="amount[]"]').prop('disabled', true);
                return true;
            });

            //$("#subscription-amount").show(); doesn't work with attr 'readonly'
            $('input[name="amount[]"]').each(function(index) {
                $(this).removeAttr("hidden");
            });

            $('.floating-amount-js').show();

            totalAmount = getTotalAmount();

            weight = getTotalWeight();

            var ratios = getRatio();

            var $amounts = $('input[name="amount[]"]'); //generate an array of amount

            $amounts.each(function(index) {
                var ratio = ratios[index];
                var value = ratio.toFixed(2);
                $(this).val(value);
            });

            $('#amount-total').on('change', function() {
                if (parseFloat($('#amount-total').val()) < 0)
                    $('#amount-total').val(0);

                totalAmount = getTotalAmount();

                weight = getTotalWeight();
                var ratios = getRatio();

                $amounts.each(function(index) {
                    var ratio = ratios[index];
                    var value = ratio.toFixed(2);
                    $(this).val(value);
                });

            });

            $('input[name="checkboxes[]"]').on('change', function() {
                var checkboxes = $('input[name="checkboxes[]"]');
                var weights = $('input[name="weights[]"]');
                for (var i = 0; i < checkboxes.length; i++) {
                    if (!$(checkboxes[i]).prop('checked')) {
                        $(weights[i]).val(0);
                    } else {
                        if ($(weights[i]).val() == 0) {
                            $(weights[i]).val(1);
                        }
                    }
                }
                totalAmount = getTotalAmount();
                weight = getTotalWeight();
                var ratios = getRatio();

                $amounts.each(function(index) {
                    var ratio = ratios[index];
                    var value = ratio.toFixed(2);
                    $(this).val(value);
                });

            });

            $('input[name="weights[]"]').on('change', function() {
                var checkboxes = $('input[name="checkboxes[]"]');
                var weights = $('input[name="weights[]"]');
                for (var i = 0; i < checkboxes.length; i++) {
                    if ($(weights[i]).val() == 0) {
                        $(checkboxes[i]).prop('checked', false);
                    }
                    if ($(weights[i]).val() > 0) {
                        $(checkboxes[i]).prop('checked', true);
                    }
                }
                totalAmount = getTotalAmount();
                weight = getTotalWeight();
                var ratios = getRatio();

                $amounts.each(function(index) {
                    var ratio = ratios[index];
                    var value = ratio.toFixed(2);
                    $(this).val(value);
                });

            });

            <?php if ($operation_name == "edit") { ?>
                $('#delete-operation-button').on('click', function() {
                    event.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        html: 'Do you really want to delete this operation ?' +
                            '<br>' +
                            'This process cannot be undone.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            deleteOperation();
                        }
                    });
                });
            <?php } ?>

            $('input').on('input', function() {
                console.log("formChanged " + formChanged);
                formChanged = true;
            });


            $('#back-button-edit').on('click', handleBackButtonClick);
            $('#back-button-add').on('click', handleBackButtonClick);

        });


        function handleBackButtonClick(event) {
            if (formChanged) {
                event.preventDefault();
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
        }


        async function deleteOperation() {
            <?php if ($operation_name == "edit") { ?>
                try {
                    await $.post("operation/delete_operation_service/" + <?= $operation->id ?>, null);
                    window.location.href = "tricount/show_tricount/<?= $operation->tricount->id ?>";

                } catch (e) {

                }
            <?php } ?>
        }

        function getTotalAmount() {
            if ($('#amount-total').val() !== "") {
                totalAmount = parseFloat($('#amount-total').val());
            } else totalAmount = 0;

            return totalAmount;
        }

        function getTotalWeight() {
            totalWeight = 0;
            $('input[name="weights[]"]').each(function() {
                var val = $(this).val();
                if (val !== "") {
                    totalWeight += parseFloat(val);
                }
            });

            // console.log("js total weight " +totalWeight );
            return totalWeight;
        }

        function getRatio() {
            var weightValues = $('input[name="weights[]"]').map(function() {
                var val = $(this).val();
                if (val !== '') {
                    return parseFloat($(this).val());
                }
            }).get();

            function calculateRatio(weight) {
                if (totalWeight > 0) {
                    return totalAmount / totalWeight * weight;
                } else {
                    return 0;
                }
            }

            var ratios = $.map(weightValues, calculateRatio);
            return ratios;
        }
    </script>

    <noscript>
        <style>
            .floating-amount-js {
                display: none;
            }
        </style>
    </noscript>

</head>

<body>
    <header>
        <div class="container p-3 mb-3 text-dark" style="background-color:#E3F2FD">
            <div class="d-flex justify-content-between">
                <?php if ($operation_name == "add") { ?>
                    <a class="btn btn-outline-danger" id='back-button-add' href="tricount/show_tricount/<?= $tricount->id; ?>">Back</a>
                <?php } else { ?>
                    <a class="btn btn-outline-danger" id='back-button-edit' href="operation/show_operation/<?= $operation->id; ?>">Back</a>
                <?php }; ?>
                <div class="text-secondary fw-bold mt-2"><?= $tricount->title ?> &#32;<i class="bi bi-caret-right-fill"></i> &#32; Expenses </div>
                <button type="submit" class="btn btn-primary" form="form1">Save</button>
            </div>
        </div>
    </header>
    <div class="container-sm">
        <?php if ($operation_name == "add") {
            $action = "operation/add_operation/$tricount->id";
        } else {
            $action = "operation/edit_operation/$operation->id";
        }
        ?>
        <div class="form-group">
            <form method='post' action=<?= $action ?> enctype='multipart/form-data' id="form1">
                <div class="input-group mb-3 has-validation">
                    <input type="text" class="form-control<?php echo count($errors_title) != 0 ? ' is-invalid' : '' ?>" name='title' id='title' placeholder="Title" value="<?= $title ?>">
                </div>
                <?php if (count($errors_title) != 0) : ?>
                    <div class='errors'>
                        <ul>
                            <?php foreach ($errors_title as $error) : ?>
                                <li class="text-danger"><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <div class="input-group mb-3">
                    <input type="number" class="form-control<?php echo count($errors_amount) != 0 ? ' is-invalid' : '' ?>" step="0.01" name='amount-total' id='amount-total' value="<?= $amount ?>" placeholder="Amount">
                    <span class="input-group-text">EUR</span>
                </div>
                <?php if (count($errors_amount) != 0) : ?>
                    <div class='errors'>
                        <ul>
                            <?php foreach ($errors_amount as $error_amount) : ?>
                                <li class="text-danger"><?= $error_amount ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <label for="date">Date</label>
                <div>
                    <input type="date" class="form-control mt-2 mb-2" id="date" name="date" required value="<?= $date ?>">
                </div>
                <?php if (count($errors_date) != 0) : ?>
                    <div class='errors'>
                        <ul>
                            <?php foreach ($errors_date as $error_date) : ?>
                                <li class="text-danger"><?= $error_date ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <label for="payer">Paid by:</label>
                <select class="form-control mt-2" name="payer" id="payer">
                    <?php foreach ($subscriptions as $subscription) : ?>
                        <option value="<?= $subscription->id; ?>" <?php echo $payer != null && $subscription->id == $payer->id ? "selected" : "" ?>><?= $subscription->full_name; ?></option>
                    <?php endforeach; ?>
                </select>
                <div id="errors-container">
                    <p class="mt-2">For whom ?(select at least one)</p>
                    <?php foreach ($subscriptions as $subscription) : ?>

                        <div class='input-group input-group-lg'>
                            <div class="input-group-text mb-3">
                                <input type="checkbox" id="checkbox<?= $subscription->id ?>" class="form-check-input" name="checkboxes[]" value="<?= $subscription->id ?>" <?php if (in_array($subscription->id, $checkboxes)) {
                                                                                                                                                                                echo 'checked';
                                                                                                                                                                            } ?>>
                            </div>
                            <div class="input-group-text mb-3 w-50">
                                <span class="text"><?= $subscription->full_name; ?></span>
                            </div>
                            <?php $weight = 0; ?>
                            <?php for ($i = 0; $i < count($weights); $i++) : ?>
                                <?php if ($ids[$i] == $subscription->id) : ?>
                                    <?php $weight = $weights[$i]; ?>
                                <?php endif; ?>
                            <?php endfor; ?>
                            <div class="form-floating floating-amount-js" id="floating-amount-<?= $subscription->id ?>">
                                <input type="number" id="subscription-amount<?= $subscription->id ?>" step="0.01" class="form-control mb-3" name="amount[]" value="0" readonly hidden>
                                <label for="subscription-amount<?= $subscription->id ?>">Amount</label>
                            </div>
                            <div class="form-floating">
                                <input type="number" id="weights<?= $subscription->id ?>" class="form-control mb-3" name="weights[]" min="0" value="<?= $weight ?>">
                                <label for="weights<?= $subscription->id ?>">Weight</label>
                            </div>
                            <input type="hidden" name="ids[]" value="<?= $subscription->id ?>">
                        </div>
                    <?php endforeach; ?>
                    <?php if (count($errors_checkbox) != 0) : ?>
                        <div class='errors'>
                            <ul>
                                <?php foreach ($errors_checkbox as $error) : ?>
                                    <li class="text-danger"><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <?php if (count($errors_weights) != 0) : ?>
                        <div class='errors'>
                            <ul>
                                <?php foreach ($errors_weights as $error) : ?>
                                    <li class="text-danger"><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
            </form>
            </div>
        </div>
    <?php if ($operation_name == "edit") { ?>
        <footer class="footer mt-3 w-100">
            <a class="btn btn-danger w-100" id="delete-operation-button" href="operation/delete_operation/<?= $operation->id; ?>">Delete</a>
        </footer>
    <?php }; ?>
    </div>
</body>

</html>