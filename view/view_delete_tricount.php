<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Delete Tricount</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
	integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
  <style>
        
.table{
    border-radius: 5px;
}
  </style>
    </head>
    <body>

    <div class="container-sm">

        
            <div style="padding: 20px; border: solid 1px lightgrey; border-radius: 5px; position: absolute; top: 50%; transform: translateY(-50%); left: 2%; width: 96%; border-radius: 5px">
                    <div class="text-center text-danger mt-3 pb-2 display-1"><i class="fa-regular fa-trash-can"></i></div>
                    <div class="text-center" style="border-bottom:none">                        
                        <p class="text-danger" ><span class="h2"> Are you sure?</span></p>
                    </div>
                        <hr/>
              

                    <div class="text-center">
                        <div class="text-danger">
                        Do you really want to delete tricount <b>"<?=$tricount->title;?>"</b><br>
                        and all of its dependencies?<br>
                        <br>
                        This process cannot be undone.
                        </div>
                        <br>
                        <a class="navbar-brand" href="tricount/edit_tricount/<?=$tricount->id;?>">
                            <button type="button" class="btn btn-secondary">Cancle</button>  </a>
                        
                        <button type="submit" class="btn btn-danger" form="form1">Delete</button>                           
        
                    </div>  
            </div>     
      

            <form class='link' action='tricount/delete/<?=$tricount->id; ?>' method='post' id="form1" >
                <input type='hidden' name='id_tricount' value='<?=$tricount->id;?>' >
            </form> 


        
        <?php if (count($errors) != 0): ?>
                <div class="text-danger">
                    <p>Please correct the following error(s) :</p>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
         <?php endif; ?>

    </div>

    </body>
</html>