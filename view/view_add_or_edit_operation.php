<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?=$page_title?></title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
	integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    </head>
    <header>
        <div class="container p-3 mb-3 text-dark" style="background-color:#E3F2FD">
            <div class="d-flex justify-content-between">
            <?php if($operation_name=="add"){?>
        <a class="navbar-brand" href="tricount/show_tricount/<?=$tricount->id;?>"><button type="button" class="btn btn-outline-danger">Back</button></a>
        <?php }else{?>
        <a class="navbar-brand" href="operation/show_operation/<?=$operation->id;?>"><button type="button" class="btn btn-outline-danger">Back</button></a>
        </a>
        <?php }; ?> 
            <div class="text-secondary fw-bold mt-2"><?=$tricount->title?> &#32<i class="bi bi-caret-right-fill"></i> &#32 Expenses </div>
            <button type="submit" class="btn btn-primary" form="form1">Save</button>
            </div>
        </div>
    </header>
    <body>
        <div class="container-sm">
            <?php if($operation_name=="add"){
                $action = "operation/add_operation/$tricount->id";
            }else{
                $action = "operation/edit_operation/$operation->id";}
            ?>
            <div class="form-group">
            <form method='post' action=<?=$action?> enctype='multipart/form-data' id="form1">
            <div class="input-group mb-3 has-validation">    
                <input type="text" class="form-control <?php echo count($errors_title)!=0 ? 'is-invalid' : ''?> " name='title' id='title' rows='1' placeholder="Title" value=<?=$title?>>
            </div>
                <?php if (count($errors_title) != 0): ?>
                <div class='errors'>
                    <ul>
                        <?php foreach ($errors_title as $error): ?>
                            <text class="text-danger"><li><?= $error ?></li></text>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
                <div class="input-group mb-3">
                <input type="number" class="form-control <?php echo count($errors_amount)!=0 ? 'is-invalid' : ''?>" step="0.01" name='amount' id='amount' value="<?=$amount?>" placeholder="Amount">
                <span class="input-group-text">EUR</span>            
                </div>
                <?php if (count($errors_amount) != 0): ?>
                <div class='errors'>
                    <ul>
                        <?php foreach ($errors_amount as $error_amount): ?>
                            <text class="text-danger"><li><?= $error_amount ?></li></text>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
                <label for="date">Date</label>
                <input type="date" class="form-control mt-2 mb-2" id="date" name="date" required value="<?=$date?>">
                <?php if (count($errors_date) != 0): ?>
                <div class='errors'>
                    <ul>
                        <?php foreach ($errors_date as $error_date): ?>
                            <text class="text-danger"><li><?= $error_date ?></li></text>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
                <label for="paidby">Paid by:</label>
                            <select class="form-control mt-2" name="payer">
                            <?php foreach ($subscriptions as $subscription): ?>  
                            <option value="<?=$subscription->full_name; ?>"><?=$subscription->full_name; ?></option>  
                            <?php endforeach; ?>
                            </select>                           
                </div>
                <p>For whom ?(select at least one)</p>
                    <?php foreach ($subscriptions as $subscription): ?>
                        <div class='input-group input-group-lg'>  
                        <div class="input-group-text mb-3">
                        <input type="checkbox" class="form-check-input" name="checkboxes[]" value="<?=$subscription->id?>"
                        <?php if (in_array($subscription->id, $checkboxes)) { echo 'checked'; } ?>
                        >
                        </div>
                        <div class="input-group-text mb-3 w-50">
                        <span class="text"><?=$subscription->full_name;?></span>
                        </div>
                        <?php $weight = 0; ?>
                        <?php for($i=0; $i<count($weights); $i++): ?>
                            <?php if($ids[$i]==$subscription->id): ?>
                                <?php $weight = $weights[$i]; ?>
                            <?php endif; ?>
                        <?php endfor; ?>
                        <input type="number" class="form-control mb-3" id="weight" name="weights[]" min="0" max="<?=$nb_subscriptions?>" value="<?=$weight?>">
                        <input type="hidden" id="ids" name="ids[]" value="<?=$subscription->id?>">
                    <?php endforeach; ?>                 
                    <?php if(count($errors_checkbox)!=0) : ?>
                            <div class='errors'>
                                <ul>
                                    <?php foreach ($errors_checkbox as $error): ?>
                                        <text class="text-danger"><li><?= $error ?></li></text>
                                    <?php endforeach; ?>
                                    </ul>
                            </div>
                        <?php endif; ?>
                    <?php if(count($errors_weights)!=0) : ?>
                            <div class='errors'>
                                <ul>
                                    <?php foreach ($errors_weights as $error): ?>
                                        <text class="text-danger"><li><?= $error ?></li></text>
                                    <?php endforeach; ?>
                                    </ul>
                            </div>
                        <?php endif; ?>               
                </div>
            </form>
        </div>
        <?php if($operation_name=="edit"){?>
            <footer class="footer mt-auto w-100">   
                <a class="btn btn-danger w-100" href="operation/delete_operation/<?=$operation->id;?>" method="post">Delete</a>
            </footer>
            <?php }; ?>
    </body>
</html>