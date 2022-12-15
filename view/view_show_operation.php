<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Show Operation</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
      
    </head>
    <body>
        <header>
        <a href="tricount/show_tricount/<?=$operation->tricount->id; ?>"><button type="button" class="btn btn-primary btn-block">Back</button></a>
        <div class="title"><?=$operation->tricount->title?>&#9654; <?=$operation->title; ?> </div>
        <a href="tricount/edit_operation/<?=$operation->id; ?>"><button type="button" class="btn btn-primary btn-block">Edit</button></a>
        </header>
        <div class="main">
            <p><?php echo $operation->amount." €"; ?></p>
            <p>Paid by <?=$operation->initiator->full_name; ?></p>
            <p> <?=$operation->operation_date; ?></p>
            <?php if(Repartition::include_user($user,$operation)): ?>
                <?php echo " For ". $operation->tricount->get_nb_participants_including_creator()." participants, including me" ?>
            <?php else: ?>
                <?php echo " For ". $operation->tricount->get_nb_participants_including_creator()." participants" ?>
            <?php endif; ?>
            <ul>
            <?php foreach ($repartitions as $repartition):  ?>
                <li> <?=$repartition->user->full_name; ?> 
                <?php if($repartition->user==$user): ?>
                (me)
                <?php endif; ?>
                <?php echo " -- ".Repartition::get_amount_by_user_and_operation($repartition->user, $repartition->operation)." €"; ?> 
                    
            <?php endforeach; ?>
                </li>
            </ul>          
        <a href="operation/show_operation/<?=$operation->id-1;?>"><button type="button" class="btn btn-primary btn-block">Previous</button></a> 
        <a href="operation/show_operation/<?=$operation->id+1;?>"><button type="button" class="btn btn-primary btn-block">Next</button></a> 
       
        </div>        


        
    </body>
</html>