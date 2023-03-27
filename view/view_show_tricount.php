<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Depenses</title>
    <base href="<?= $web_root ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <script src="lib/jquery-3.6.4.min.js"></script>
    <script>
        $(function() {
            var expenses = $('.list-group');
            $('#sort-by').show();
            $('#sort_title').show();
            $('#sort-by').change(function() {
                var selected = $(this).val();
                expenses.sort(function(a, b) {
                    if (selected == "amount-asc" || selected=="amount-desc") {
                        valA = parseFloat($(a).find('.amount').text());
                        valB = parseFloat($(b).find('.amount').text());
                        return selected == "amount-asc" ? valA - valB : valB - valA;
                    } else if (selected == "date-asc" || selected == "date-desc") {
                        valA = new Date($(a).find('.date').text().split('/').reverse().join('-'));
                        valB = new Date($(b).find('.date').text().split('/').reverse().join('-'));
                        return selected == "date-asc" ? valA - valB : valB - valA;
                    } else if (selected == "initiator-asc" || selected == "initiator-desc") {
                        valA = $(a).find('.initiator').text();
                        valB = $(b).find('.initiator').text();
                        return selected == "initiator-asc" ? valA.localeCompare(valB) : valB.localeCompare(valA);
                    } else if (selected == "title-asc"||selected=="title-desc") {
                        valA = $(a).find('.title').text();
                        valB = $(b).find('.title').text();
                        return selected == "title-asc" ? valA.localeCompare(valB) : valB.localeCompare(valA);
                    }
                })

                expenses.detach().appendTo('.container-sm');
            });
        });
    </script>

</head>

<body>
    <header>
        <div class="container p-3 mb-3 text-dark" style="background-color:#E3F2FD">
            <div class="d-flex justify-content-between">
                <a class="btn btn-outline-danger" href="tricount/index">Back</a>
                <div class="text-secondary fw-bold mt-2"><?= $tricount->title ?> &#32;<i class="bi bi-caret-right-fill"></i> &#32; Expenses </div>
                <a class="btn btn-primary" href="tricount/edit_tricount/<?= $tricount->id; ?>">Edit</a>
            </div>
        </div>
    </header>
    <div class="container-sm">
        <?php if ($tricount->get_nb_participants() == 0) : ?>
            <ul class="list-group list-unstyled align-items-center">
                <li class="m-3 border w-100 rounded">
                    <div class="text-center">
                        <div class="h3 p-3 border-bottom border-secondary" style="background-color: #F7F7F7">You are alone!</div>
                        <div class="text p-3">Click below to add your friends!</div>
                        <a class="btn btn-primary mb-3" href="tricount/edit_tricount/<?= $tricount->id; ?>">Add Friends</a>
                    </div>
                </li>
            </ul>
        <?php elseif (!$tricount->get_depenses()) : ?>
            <ul class="list-group list-unstyled align-items-center">
                <li class="m-3 border border-secondary w-100 rounded">
                    <div class="text-center">
                        <div class="h3 p-3 border-bottom border-secondary" style="background-color: #F7F7F7"><?php echo "Your tricount is empty!" ?></div>
                        <div class="text p-3">Click below to add your first expense!</div>
                        <a class="btn btn-primary mb-3" href="operation/add_operation/<?= $tricount->id; ?>">Add an expense</a>
                    </div>
                </li>
            </ul>
        <?php else : ?>
            <a class="btn btn-success w-100 mb-3 p-2" href="tricount/show_balance/<?= $tricount->id; ?>"><i class="bi bi-arrow-left-right"></i> View Balance</a>
            <div class="text" id="sort_title" style="display:none">Order expenses by :</div>
            <select id="sort-by" class="form-select mb-2" style="display:none;">
                <option value="amount-asc">Amount &#9650;</option>
                <option value="amount-desc">Amount &#9660;</option>
                <option value="date-asc">Date &#9650;</option>
                <option value="date-desc" selected>Date &#9660;</option>
                <option value="initiator-asc">Initiator &#9650;</option>
                <option value="initiator-desc">Initiator &#9660;</option>
                <option value="title-asc">Title &#9650;</option>
                <option value="title-desc">Title &#9660;</option>
            </select>
            <?php $depenses = $tricount->get_depenses(); ?>
            <?php foreach ($depenses as $depense) :  ?>
                <ul class="list-group w-100">
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-2 me-auto">
                            <div class="text">
                                <a href='operation/show_operation/<?= $depense->id; ?>' class="stretched-link" style='text-decoration:none ; color:inherit'></a>
                                <p class="title"><span class="fw-bold"><?= $depense->title ?></span></p>
                                <span class="initiator"><?php echo "Paid by " . $depense->initiator->full_name ?></span>
                            </div>
                        </div>
                        <div class="text-end">
                            <p class="amount"><span class="fw-bold"><?= round($depense->amount, 2) ?>&euro;</span></p>
                            <span class="date"><?= date('d/m/Y', strtotime($depense->operation_date)); ?></span>
                        </div>
                    </li>
                </ul>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <footer class="footer mt-auto fixed-bottom">
        <div class="container p-1 text-dark" style="background-color:#E3F2FD">
            <div class="position relative">
                <div class="position-absolute top-0 start-50 translate-middle">
                    <a class="btn btn-primary btn-lg rounded-circle" href="operation/add_operation/<?= $tricount->id ?>">+</a>
                </div>
            </div>
            <div class="d-flex p-1 justify-content-beetween w-100">
                <div class="me-auto">
                    <div class="text">MY TOTAL</div>
                    <div class="fw-bold"><?= $tricount->get_my_total($user) . " &euro;" ?></div>
                </div>
                <div class="text-end">
                    <div class="text">TOTAL EXPENSES</div>
                    <div class="fw-bold"><?= $tricount->get_total() . "&euro;" ?></div>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>