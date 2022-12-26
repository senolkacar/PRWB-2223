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
                <input type="text" name='title' id='title' rows='1' placeholder="Title" value=<?=$_SESSION["title"]?>> <br>
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
                <input type="number" step="0.01" name='amount' id='amount' value="<?=$_SESSION["amount"]?>" placeholder="Amount"> <br>
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
                <input type="date" id="date" name="date" required value="<?=$_SESSION["date"]?>">
                <?php if (count($errors_date) != 0): ?>
                <div class='errors'>
                    <ul>
                        <?php foreach ($errors_date as $error_date): ?>
                            <li><?= $error_date ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
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
                        <input type="checkbox" name="checkboxes[]" value="<?=$subscription->id?>"
                        <?php foreach($users as $user): ?>
                            <?php if($user->id == $subscription->id): ?>
                                checked
                            <?php endif; ?>
                        <?php endforeach; ?>
                        ><?=$subscription->full_name;?>
                        <label for="weight">Weight</label>
                        <?php foreach($repartitions as $repartition): ?>
                        <?php if($repartition->user->id == $subscription->id): ?>
                            <?php $weight[$subscription->full_name] = $repartition->weight; ?>
                        <?php endif; ?>
                        <?php endforeach; ?>
                        <input type="number" id="weight" name="weights[]" min="0" max="<?=$nb_subscriptions?>" value="<?=empty($weight[$subscription->full_name]) ? 0 : $weight[$subscription->full_name] ?>">
                        <input type="hidden" id="ids" name="ids[]" value="<?=$subscription->id?>">
                         <br>
                    <?php endforeach; ?>
                    <?php if(count($errors_checkbox)!=0) : ?>
                            <div class='errors'>
                                <ul>
                                    <?php foreach ($errors_checkbox as $error): ?>
                                        <li><?= $error ?></li>
                                    <?php endforeach; ?>
                                    </ul>
                            </div>
                        <?php endif; ?>
                    <?php if(count($errors_weights)!=0) : ?>
                            <div class='errors'>
                                <ul>
                                    <?php foreach ($errors_weights as $error): ?>
                                        <li><?= $error ?></li>
                                    <?php endforeach; ?>
                                    </ul>
                            </div>
                        <?php endif; ?>               
                </div>


                <button type="submit" class="btn btn-primary">Save</button>
            </form>
            <?php if($operation_name=="edit"){?>
                <a class="navbar-brand" href="operation/delete_operation/<?=$operation->id;?>">
                <button type="button" class="btn btn-primary">Delete</button>
                </a>
            <?php }; ?>

            




        </div>
    </body>
</html>