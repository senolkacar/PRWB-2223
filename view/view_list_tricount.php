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
        <header>
            <div  class="container p-3 mb-3 text-dark" style="background-color: #E3F2FD;"> 
                <div class="d-flex justify-content-between mb-3">
                    <div class="h2">Your tricounts </div>
                    <a href="tricount/add_tricount" class="btn btn-primary btn-block,sticky-sm-top,">Add</a>
                </div>
            </div>
        </header>

        <div class="container-sm">
            <ul class="list-group"> 
            <?php foreach ($tricounts as $row):  ?>
                <?php $tricount = $row[0]; $count=$row[1]; ?>

                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="ms-2 me-auto">
                        <div class="fw-bold">
                            <a href='tricount/show_tricount/<?=$tricount->id?>' class="stretched-link" 
                             style="text-decoration:none ; color:inherit;">
                             <?=$tricount->title?></a>
                        </div> 
                       
                        <?php if(!$tricount->description || $tricount->description == "NULL" ): ?>
                            No description
                        <?php else: ?>
                            <?=$tricount->description ?>
                        <?php endif; ?>               
                    </div>
                    <span class="badge bg-transparent text-dark">
                        <?php if( $count== 0): ?>
                          you are alone
                        <?php else: ?>
                            <?php echo " with ". $count." friends" ?>
                        <?php endif; ?>
                    </span>
            <?php endforeach; ?>
            
                </li>
            </ul>    
        </div>           
        
        <footer class="footer mt-auto fixed-bottom">
            <div class="container-sm" style="margin-bottom:42px">
                <a href="user/settings" class="float-end">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#FFC107" class="bi bi-gear-fill" viewBox="0 0 16 16">
                        <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"/>
                    </svg>
                </a>  
            </div> 
        </footer>  
        
       


        
    </body>
</html>