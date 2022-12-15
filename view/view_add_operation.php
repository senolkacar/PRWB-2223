<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Add Operation</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
    <nav class="navbar navbar-light bg-light">
    <a class="navbar-brand" href="tricount/show_tricount/<?=$tricount->id;?>">
        <button type="button" class="btn btn-primary">Cancel</button>
    </a>
    <div class="title"><?=$tricount->title;?>  &#32 &#9654; &#32 New expense </div>
    
   
    </nav>
        <div class="main">
            <form method='post' action='operation/add_operation' enctype='multipart/form-data'>
                Title  <br>
                <textarea name='title' id='title' rows='1'>Title</textarea> <br>
                Amount  <br>
                <textarea name='amount' id='amount' rows='3'>Amount</textarea> <br>
                <p>Date </p>
                <p>Paid by </p>
                <p> repartition template (optional)</p>
                <p>For whom ?(select at least one)</p>
                <button type="submit" class="btn btn-primary">Save</button>
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

            <p>Add a new repartition template</p>


        </div>
    </body>
</html>