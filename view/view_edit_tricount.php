<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Edit Tricount</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
    <nav class="navbar navbar-light bg-light">
    <a class="navbar-brand" href="tricount/index">
        <button type="button" class="btn btn-primary">Back</button>
    </a>
    <p class="text-right"><?=$tricount->title?> &#32 &#9654; &#32 Edit</p>
    </nav>
        <div class="main">
            <form method='post' action='tricount/edit_tricount/<?=$tricount->id; ?>' enctype='multipart/form-data'>
                <p>Settings</p>
                Title: <br>
                <textarea name='title'  id='title'  rows='1'><?= $tricount->title; ?></textarea> <br>
                Description (optional) : <br>
                <textarea name='description' id='description'  rows='2'><?= $tricount->description; ?></textarea> <br>  

                <div class='subscriptions'>
                     <p>Subscriptions</p>
                    <fieldset style="width:150px">
                    <?=$tricount->creator->full_name;?>
                    <ul>
                        <?php foreach ($subscriptions as $subscription): ?>
                            <li><?= $subscription->user->full_name ?></li>
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


        </div>
    </body>
</html>