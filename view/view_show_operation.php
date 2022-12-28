<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Show Operation</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
	integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
      
    </head>
    <body>
        <div  class="container p-3 mb-3 text-dark" style="background-color: #E3F2FD;">
            <div class="d-flex justify-content-between mb-3"> 
                <a href="tricount/show_tricount/<?=$operation->tricount->id; ?>"><button type="button" class="btn btn-outline-danger">Back</button></a>
                <div class="h5"><?=$operation->tricount->title?><i class="bi bi-caret-right-fill"></i> <?=$operation->title; ?> </div>         
                <a href="operation/edit_operation/<?=$operation->id; ?>"><button type="button" class="btn btn-primary">Edit</button></a>
            </div> 
        </div>

        <div class="container-sm">
            <h5 class="text-center"><?php echo round($operation->amount, 2)." €"; ?></h5>
            <div class="d-flex justify-content-between mb-2"> 
                <p>Paid by <?=$operation->initiator->full_name; ?></p>
                <p> <?=$operation->operation_date; ?></p>
            </div>
           

            <?php if(Repartition::include_user($user,$operation)): ?>
                <?php echo " For ". $operation->tricount->get_nb_participants_including_creator()." participants, including <b> me </b>" ?>
            <?php else: ?>
                <?php echo " For ". $operation->tricount->get_nb_participants_including_creator()." participants" ?>
            <?php endif; ?>
            <br><br>
          
            <ul  class="list-group">
            <?php foreach ($repartitions as $repartition):  ?>
                <li class="list-group-item">
                    <div class="d-flex justify-content-between mb-2">                   
                        <div> <?=$repartition->user->full_name; ?> 
                            <?php if($repartition->user==$user): ?>
                            (me)
                            <?php endif; ?> </div>
                        <div>
                        <?php echo Repartition::get_amount_by_user_and_operation($repartition->user, $repartition->operation)." €"; ?> </div>
                    
            <?php endforeach; ?>
                </li>
            </ul> 

             <br><br>   
          
            <div  class="container p-3 mb-3 text-dark" style="background-color: #E3F2FD;">
                <div class="d-flex justify-content-between mb-3"> 
                <?php if($current_page == 0 && $pages==1): ?> 
                <?php elseif($pages>1 && $current_page == 0 ): ?> 
                    <a href="operation/show_operation/<?=$operations[$current_page+1]->id;?>"><button type="button" class="btn btn-primary btn-block">Next</button></a> 
                <?php elseif( $pages>1 && $current_page == ($pages-1)): ?>
                    <a href="operation/show_operation/<?=$operations[$current_page-1]->id;?>"><button type="button" class="btn btn-primary btn-block">Previous</button></a> 
                <?php else: ?>
                    <a href="operation/show_operation/<?=$operations[$current_page-1]->id;?>"><button type="button" class="btn btn-primary btn-block">Previous</button></a> 
                    <a href="operation/show_operation/<?=$operations[$current_page+1]->id;?>"><button type="button" class="btn btn-primary btn-block">Next</button></a> 
                <?php endif; ?>
                </div>
            </div>
       
        </div>        


        
    </body>
</html>