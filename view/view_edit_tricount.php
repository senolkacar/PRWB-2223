<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Edit Tricount</title>
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
                <a class="navbar-brand" href="tricount/index">
                <button type="button" class="btn btn-outline-danger">Back</button>
                </a>
                <div class="h2"><?=$tricount->title?> &#32<i class="bi bi-caret-right-fill"></i> &#32 Edit </div>
                <div class="h2"> </div>
            </div>
        </div>

        <div class="container-sm">
            <form method='post' action='tricount/edit_tricount/<?=$tricount->id; ?>' enctype='multipart/form-data'>
               <h2>Settings</h2>
            
                Title: <br>
                <textarea name='title'  id='title'  rows='1'><?= $tricount->title; ?></textarea> <br>
                Description (optional) : <br>
                <textarea name='description' id='description'  rows='2'><?= $tricount->description; ?></textarea> <br>  

                <div class='subscriptions'>
                     <p>Subscriptions</p>
                    <fieldset style="width:150px">
                    <ul>
                        <?php foreach ($subscriptions as $subscription): ?>
                            <li><?= $subscription->full_name ?></li>
                        <?php endforeach; ?>
                    </ul>
                    </fieldset>
                </div>
                <br>

                <div class='add-subscriber'>
                            <select name = "subscriber" id="subscriber" value="add subscriber">
                            <option value=" ">--Add a new subscriber--</option> 
                            <?php foreach ($other_users as $other_user): ?>  
                            <option value="<?=$other_user->full_name; ?>"><?=$other_user->full_name; ?></option>  
                            <?php endforeach; ?>
                            </select>                           
                
                </div>
                <br>
                <input type='submit' value='Save'>
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
                <?php elseif (strlen($success) != 0): ?>
                <p><span class='success'><?= $success ?></span></p>
            <?php endif; ?>
            <br><br>
            <form class='link' action='tricount/delete/<?=$tricount->id; ?>' method='post' >
                    <button type="submit" class="btn btn-primary">Delete this tricount</button>
             </form>
        </div>
    </body>
</html>