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
        <a href="tricount/view_balance/<?=$tricount->id; ?>"><button type="button" class="btn btn-primary btn-block">&#8633;View Balance</button></a>
            <ul>
                    <?php if($nb_participants == 0): ?>
                        --&#32 you are alone
                    <?php elseif(!$depenses): ?>
                        <?php echo "Your tricount is empty" ?>
                    <?php else: ?>
                        <?php echo "List of depenses" ?>
                        <?php foreach ($depenses as $depense):  ?>
                        <li><a href='tricount/show_operation/<?=$depense["id"]; ?>'> <?php echo $depense["title"]." ".$depense["amount"]; ?> </a>
                        <br>
                        <?php echo "Paid by ".$depense["full_name"]." ".$depense["operation_date"] ?>
                        </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <br>
                    <?php echo "MY TOTAL 0 &euro;"?>
                    <a href="operations/add_operation/<?=$tricount->id?>"><button type="button" class="btn btn-primary btn-block">+</button></a>
                    <?php echo "TOTAL EXPENSES ".$total."&euro;"?>

            </ul>       
       
        <a href="user/settings">Settings</a>
        </div>        


        
    </body>
</html>
