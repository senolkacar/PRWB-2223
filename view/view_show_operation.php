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
            <ul>

                   
                   

            </ul>      
        <a href="tricount/show_tricount/<?=$operation->id-1;?>"><button type="button" class="btn btn-primary btn-block">Previous</button></a> 
        <a href="tricount/show_tricount/<?=$operation->id+1;?>"><button type="button" class="btn btn-primary btn-block">Next</button></a> 
       
        </div>        


        
    </body>
</html>