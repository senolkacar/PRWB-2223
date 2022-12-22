<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?=$page_title?></title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
    <nav class="navbar navbar-light bg-light">
    <?php if($operation_name=="add"){?>
        <a class="navbar-brand" href="tricount/show_tricount/<?=$tricount->id;?>">
        <button type="button" class="btn btn-primary">Cancel</button>
    </a>
    <?php }else{?>
        <a class="navbar-brand" href="operation/show_operation/<?=$tricount->id;?>">
        <button type="button" class="btn btn-primary">Cancel</button>
    </a>
    <?php }; ?> 
    
    <div class="title"><?=$tricount->title;?>  &#32 &#9654; &#32 <?=$header_title;?> </div>
    
   
    </nav>
        <div class="main">
            <?php if($operation_name=="add"){
                $action = "operation/add_operation/$tricount->id";
            }else{
                $action = "operation/edit_operation/$operation->id";}
            ?>
            <form method='post' action=<?=$action?> enctype='multipart/form-data'>
                Title  <br>
                <?php if(empty($operation)): 
                    $title = "Title";
                    $amount = "Amount";
                    $date = "";
                else:
                    $title = $operation->title;
                    $amount = $operation->amount;
                    $date = $operation->operation_date;
                endif; ?>
                <textarea name='title' id='title' rows='1'><?=$title?></textarea> <br>
                <?php if (count($errors_title) != 0): ?>
                <div class='errors'>
                    <ul>
                        <?php foreach ($errors_title as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>


                Amount  <br>
                <input type="number" step="0.01" name='amount' id='amount' value="<?=$amount?>"> <br>
                <?php if (count($errors_amount) != 0): ?>
                <div class='errors'>
                    <ul>
                        <?php foreach ($errors_amount as $error_amount): ?>
                            <li><?= $error_amount ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <p>Date </p>
                <input type="date" id="date" name="date" value="<?=$date?>">
                <p>Paid by </p>

                <div class='paid_by'>
                            <select name = "payer" id="payer" value="">
                            <?php foreach ($subscriptions as $subscription): ?>  
                            <option value="<?=$subscription->full_name; ?>"><?=$subscription->full_name; ?></option>  
                            <?php endforeach; ?>
                            </select>                           
                
                </div>

               <br>
                <p>For whom ?(select at least one)</p>
                <div class='checkbox'>
                    <?php foreach ($subscriptions as $subscription): ?>  
                        <input type="checkbox" name="users[]" value="<?=$subscription->id?>"><?=$subscription->full_name;?>
                        <label for="weight">Weight</label>
                        <input type="number" id="weight" name="weights[]" min="0" max="<?=$nb_subscriptions?>">
                        <input type="hidden" id="ids" name="ids[]" value="<?=$subscription->id?>">
                         <br>
                    <?php endforeach; ?>               

                </div>


                <button type="submit" class="btn btn-primary">Save</button>
            </form>

           




        </div>
    </body>
</html>