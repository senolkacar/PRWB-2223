<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>your tricounts</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
      
    </head>
    <body>
        <div class="title">your tricounts </div>
        <?php include('menu.html'); ?>
        <div class="main">
            <ul>
            <?php foreach ($tricounts as $tricount):  ?>
                <li><?=$tricount->title ?>
                    <?php if($nb_subscrptions == 0): ?>
                        --&#32 you are alone
                    <?php else: ?>
                        <?php echo " with".$nb_subscrptions."friends" ?>
                    <?php endif; ?>
                    <br>
                    <?php if(!$tricount->description): ?>
                        No description
                    <?php else: ?>
                        <?=$tricount->description ?>
                     <?php endif; ?>
            <?php endforeach; ?>
            
            </ul>       
        <a href="logout">Log Out</a>
        <a href="user/settings">Settings</a>
        </div>        


        
    </body>
</html>