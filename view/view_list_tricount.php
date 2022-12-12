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
        <a href="tricount/add_tricount"><button type="button" class="btn btn-primary btn-block">Add</button></a>
       
        <div class="main">
            <ul>
            <?php foreach ($tricounts as $tricount):  ?>

                <li><a href='tricount/show_tricount/<?=$tricount['title'] ?>'><?=$tricount['title'] ?> </a>
                    <?php if( $tricount['subscription_count'] == 0): ?>
                        --&#32 you are alone
                    <?php else: ?>
                        <?php echo " with ". $tricount['subscription_count']." friends" ?>
                    <?php endif; ?>
                    <br>
                    <?php if(!$tricount['description'] || $tricount['description'] == "NULL" ): ?>
                        No description
                    <?php else: ?>
                        <?=$tricount['description'] ?>
                     <?php endif; ?>
            <?php endforeach; ?>
            
            </ul>       
       
        <a href="user/settings">Settings</a>
        </div>        


        
    </body>
</html>