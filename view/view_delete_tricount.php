<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Delete Tricount</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
	integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">

    </head>
    <body>

    <div class="container-sm">

        <div class="contrainer_table">
        <div class ="vertical-center">
            <table class="table">
                <tr>
                    <td class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#dc3545" class="bi bi-trash-fill" viewBox="0 0 16 16">
                        <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                        </svg>
                        <br>
                        <p class="text-danger" ><span class="h2"> Are you sure?</span></p>
                    </td>
                </tr>

                <tr>
                    <td class="text-center">

                        <div class="text-danger">
                        Do you really want to delete tricount "<?=$tricount->title;?>"<br>
                        and all of its dependencies?<br>
                        <br>
                        This process cannot be undone.
                        </div>
                        <br><br>

                        <a class="navbar-brand" href="tricount/edit_tricount/<?=$tricount->id;?>">
                            <button type="button" class="btn btn-secondary">Cancle</button>  </a>
                        
                        <button type="submit" class="btn btn-danger" form="form1">Delete</button>
                            
        
                    </td>  
                </tr>
            </table>
        </div>
        </div>
      

            <form class='link' action='tricount/delete/<?=$tricount->id; ?>' method='post' id="form1" >
                <input type='hidden' name='id_tricount' value='<?=$tricount->id;?>' >
            </form> 


        
        <?php if (count($errors) != 0): ?>
                <div class='errors'>
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