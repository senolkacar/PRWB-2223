<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Depenses</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
	integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    </head>
    <body>
        <header>
        <div class="container p-3 mb-3 text-dark" style="background-color:#E3F2FD">
            <div class="d-flex justify-content-between">
            <a class="btn btn-outline-danger" href="tricount/index">Back</a>
            <div class="text-secondary fw-bold mt-2"><?=$tricount->title?> &#32;<i class="bi bi-caret-right-fill"></i> &#32; Expenses </div>
            <a class="btn btn-primary" href="tricount/edit_tricount/<?=$tricount->id; ?>">Edit</a>
            </div>
        </div>
        </header>
        <div class="container-sm">      
                    <?php if($nb_participants == 0): ?>
            <ul class="list-group list-unstyled align-items-center">
                        <li class="m-3 border w-100 rounded">
                        <div class="text-center">
                        <div class="h3 p-3 border-bottom border-secondary" style="background-color: #F7F7F7">You are alone!</div>
                        <div class="text p-3">Click below to add your friends!</div>
                        <a class="btn btn-primary mb-3" href="tricount/edit_tricount/<?=$tricount->id; ?>">Add Friends</a>
                        </div>
                    </li>
            </ul>
                        <?php elseif(!$depenses): ?>
            <ul class="list-group list-unstyled align-items-center">
                        <li class="m-3 border border-secondary w-100 rounded">
                        <div class="text-center">
                        <div class="h3 p-3 border-bottom border-secondary" style="background-color: #F7F7F7"><?php echo "Your tricount is empty!" ?></div>
                        <div class="text p-3">Click below to add your first expense!</div>
                        <a class="btn btn-primary mb-3" href="operation/add_operation/<?=$tricount->id; ?>">Add an expense</a>   
                        </div>
                        </li>
            </ul>
                        <?php else: ?>
                        <a class="btn btn-success w-100 mb-3 p-2" href="tricount/show_balance/<?=$tricount->id; ?>"><i class="bi bi-arrow-left-right"></i> View Balance</a>   
                        <?php foreach ($depenses as $depense):  ?>
                        <ul class="list-group w-100">
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="text">
                                <a href='operation/show_operation/<?=$depense["id"]; ?>' class="stretched-link" style='text-decoration:none ; color:inherit'></a>     
                                <p class="fw-bold"><?=$depense["title"]?></p>
                                <?php echo "Paid by ".User::get_user_by_id($depense["initiator"])->full_name?>
                                </div>  
                            </div>
                            <div class="text-end">
                                <p class="fw-bold"><?=round($depense["amount"],2)?>&euro;</p>
                                <?=$depense["operation_date"]?>
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
        <a class="btn btn-primary btn-lg rounded-circle" href="operation/add_operation/<?=$tricount->id?>">+</a>
            </div>
        </div>
                    <div class="d-flex p-1 justify-content-beetween w-100">
                        <div class="me-auto">
                            <div class="text">MY TOTAL</div>
                            <div class="fw-bold"><?=$mytotal." &euro;"?></div>
                        </div>
                        <div class="text-end">
                            <div class="text">TOTAL EXPENSES</div>
                            <div class="fw-bold"><?=$total."&euro;"?></div>
                        </div>
                    </div>
        </div>
        </footer>         
    </body>
</html>

