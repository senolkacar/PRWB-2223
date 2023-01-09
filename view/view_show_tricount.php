<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Depenses</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
	integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    </head>
    <body>
        <header>
        <div class="container p-3 mb-3 text-dark" style="background-color:#E3F2FD">
            <div class="d-flex justify-content-between">
            <a class="navbar-brand" href="tricount/index"><button type="button" class="btn btn-outline-danger">Back</button></a>
            <div class="h2"><?=$tricount->title?> &#32<i class="bi bi-caret-right-fill"></i> &#32 Edit </div>
            <a class="navbar-brand" href="tricount/edit_tricount/<?=$tricount->id; ?>"><button type="button" class="btn btn-primary">Edit</button></a>
            </div>
        </div>
        </header>
        <div class="container-sm">      
            <ul class="list-group align-items-center">
                    <?php if($nb_participants == 0): ?>
                        <span class="m-3 border border-secondary w-100 rounded">
                        <div class="text-center">
                        <div class="h3 p-3 border-bottom border-secondary" style="background-color: #F7F7F7">You are alone!</div>
                        <div class="text p-3">Click below to add your friends!</div>
                        <a class="btn btn-primary mb-3" href="tricount/edit_tricount/<?=$tricount->id; ?>">Add Friends</a>
                        </div>
                        </span>
                        <?php elseif(!$depenses): ?>
                        <span class="m-3 border border-secondary w-100 rounded">
                        <div class="text-center">
                        <div class="h3 p-3 border-bottom border-secondary" style="background-color: #F7F7F7"><?php echo "Your tricount is empty!" ?></div>
                        <div class="text p-3">Click below to add your first expense!</div>
                        <a class="btn btn-primary mb-3" href="operation/add_operation/<?=$tricount->id; ?>">Add an expense</a>   
                        </div>
                        </span>
            </ul>
                        <?php else: ?>
                        <a class="btn btn-success w-100" href="tricount/show_balance/<?=$tricount->id; ?>">&#8633;View Balance</a>   
                        <?php foreach ($depenses as $depense):  ?>
                    <ul class="list-group w-100">
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                            <a href='operation/show_operation/<?=$depense["id"]; ?>' style="text-decoration:none ; color:inherit">
                            
                            <div class="fw-bold">
                        <?php 
                            echo $depense["title"]." ".round($depense["amount"],2) ?> </a>
                            </div>
                        <br>
                        <?php echo "Paid by ".User::get_user_by_id($depense["initiator"])->full_name." ".$depense["operation_date"] ?>
                        </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <br>
                    <?php echo "MY TOTAL ".$mytotal." &euro;"?>
                    <a href="operation/add_operation/<?=$tricount->id?>"><button type="button" class="btn btn-primary btn-block">+</button></a>
                    <?php echo "TOTAL EXPENSES ".$total."&euro;"?>

                </ul>       
       
        <a href="user/settings">Settings</a>
        </div>        


        
    </body>
</html>

