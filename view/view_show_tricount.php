<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Depenses</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
      
    </head>
    <body>
        <header>
        <a href="tricount/index"><button type="button" class="btn btn-primary btn-block">Back</button></a>
        <div class="title"><?=$tricount->title?>&#9654; Expenses </div>
        <a href="tricount/edit_tricount/<?=$tricount->id; ?>"><button type="button" class="btn btn-primary btn-block">Edit</button></a>
        </header>
        <div class="main">
        <a href="tricount/show_balance/<?=$tricount->id; ?>"><button type="button" class="btn btn-primary btn-block">&#8633;View Balance</button></a>
            <ul>
                    <?php if($nb_participants == 0): ?>
                        --&#32 you are alone
                        <p>Click below to add your friends!</p>
                        <a href="tricount/edit_tricount/<?=$tricount->id; ?>"><button type="button" class="btn btn-primary btn-block">Add Friends</button></a>
                    <?php elseif(!$depenses): ?>
                        <?php echo "Your tricount is empty" ?>
                        <p>Click below to add your first expense!</p>
                        <a href="operation/add_expense/<?=$tricount->id; ?>"><button type="button" class="btn btn-primary btn-block">Add an expense</button></a>
                        
                    <?php else: ?>
                        <?php echo "List of depenses" ?>
                        <?php foreach ($depenses as $depense):  ?>
                        <li>
                        <?php 
                        echo $depense["title"]." ".round($depense["amount"],2) ?>
                        <br>
                        <?php echo "Paid by ".User::get_user_by_id($depense["initiator"])->full_name." ".$depense["operation_date"] ?>
                        </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <br>
                    <?php echo "MY TOTAL ".$mytotal." &euro;"?>
                    <a href="operations/add_operation/<?=$tricount->id?>"><button type="button" class="btn btn-primary btn-block">+</button></a>
                    <?php echo "TOTAL EXPENSES ".$total."&euro;"?>

            </ul>       
       
        <a href="user/settings">Settings</a>
        </div>        


        
    </body>
</html>

