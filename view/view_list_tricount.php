<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>your tricounts</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" 
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        
      
    </head>
    <body >
        <div  class="container p-3 mb-3 bg-info text-dark"> 
            <div class="d-flex justify-content-between mb-3">
                <div class="h2">Your tricounts </div>
                <a href="tricount/add_tricount"><button type="button" 
                class="btn btn-primary btn-block,sticky-sm-top,">Add</button></a>
            </div>
        </div>

        <div class="container-sm">
            <ul class="list-group"> 
            <?php foreach ($tricounts as $tricount):  ?>

                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="ms-2 me-auto">
                        <div class="fw-bold">
                            <a href='tricount/show_tricount/<?=$tricount['id']?>' style="text-decoration:none ; color:inherit;">
                             <?=$tricount['title']?> 
                        </div> 
                       
                        <?php if(!$tricount['description'] || $tricount['description'] == "NULL" ): ?>
                            No description
                        <?php else: ?>
                            <?=$tricount['description'] ?>
                        <?php endif; ?>               
                    </div>
                    <span class="badge text-bg-light">
                        <?php if( $tricount['subscription_count'] == 0): ?>
                         --&#32 you are alone
                        <?php else: ?>
                            <?php echo " with ". $tricount['subscription_count']." friends" ?>
                        <?php endif; ?>
                    </span>
                    
            <?php endforeach; ?>
            </a>
                </li>
            </ul>    
        </div>           
        
        <br><br>
        <div class="container-sm">
        <a href="user/settings" class="float-end"> Settings
        <i class="bi bi-gear-fill"></i>
        </a>  
        </div> 
        
       


        
    </body>
</html>