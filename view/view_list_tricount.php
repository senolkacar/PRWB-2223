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
                    <?php if($nbParticipation == 0): ?>
                        --&#32 you are alone
                    <?php else: ?>
                        <?php echo " with".$nbParticipation."friends" ?>
                    <?php endif; ?>

                    <br>
                     <?=$tricount->description ?>
            <?php endforeach; ?>
            
            </ul>       
        
        </div>        


        
    </body>
</html>