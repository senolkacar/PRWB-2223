<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Depenses</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
      
    </head>
    <body>
        <header>
        <a href="tricount/show_tricount/"<?=$tricount->id?>><button type="button" class="btn btn-primary btn-block">Back</button></a>
        <div class="title"><?=$tricount->title?>&#9654; Balance </div>
        </header>
        <div class="main">
            <ul>
                <li>User Balance</li>
            </ul>       
       
        <a href="user/settings">Settings</a>
        </div>        


        
    </body>
</html>

