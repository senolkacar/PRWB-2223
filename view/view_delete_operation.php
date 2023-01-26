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
    </head>
    <body>

    <div class="container d-flex align-items-center min-vh-100">
    <div class="container-sm border rounded">
            <div class="text-center text-danger mt-3 pb-2 display-1"><i class="fa-regular fa-trash-can"></i></div>
            <div class="text-center text-danger pb-3 border-bottom fs-1">
            Are you sure ?
            </div>
            <div class="text-center text-danger mt-3">
            Do you really want to delete operation <text class="fw-bold">"<?=$operation->title;?></text>" ?
            </div>          
            <div class="text-center text-danger mt-3">
            This process cannot be undone.
            </div>
            <div class="d-flex justify-content-center mt-3 pb-3">
            <a class="btn btn-secondary" href="operation/edit_operation/<?=$operation->id;?>">Cancel</a>
            <button type="submit" class="btn btn-danger ms-2" form="form1">Delete</button>  
            </div>
            <form class='link' action='operation/delete_operation/<?=$operation->id?>' method='post' id="form1" >
                <input type='hidden' name='operationid' value='<?=$operation->id;?>' >
            </form> 

            <div class ="container-sm">
            <?php if (count($errors) != 0): ?>
                        <div class='errors'>
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li class="text-danger"><?= $error?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
            <?php endif; ?>
            </div>
    </div>
    </div>     

    </body>
</html>