<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Balance</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
	integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
      
    </head>
    <body>
        <header>
        <div class="container p-2 mb-3 text-dark" style="background-color:#E3F2FD">
        <div class="d-flex justify-content-between">
        <a href="tricount/show_tricount/<?=$id?>"><button type="button" class="btn btn-outline-danger btn-block">Back</button></a>
        <div class="text-secondary fw-bold mt-2"><?=$tricount->title?>&#32<i class="bi bi-caret-right-fill"></i> &#32 Balance </div>
        </div>
        </div>
        </header>
        <div class="container-sm">
            <ul class="list-group w-100">
                <?php foreach($balance as $full_name => $amount): ?>
                <?php if($amount!=0):?>
                <?php if($amount<0):?>
                <li class="d-flex justify-content-center align-items-center">
                <div class="progress-bar bg-danger rounded-start" role="progressbar" style="width: 100%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"><text class="text-end me-2 <?php echo ($user == $full_name) ? 'fw-bold' : '';?>"><?=$amount?><i class="bi bi-currency-euro"></i></text></div>
                <div class="text-start p-1 w-100 <?php echo ($user == $full_name) ? 'fw-bold' : '';?>"><?=$full_name?><?php echo ($user == $full_name) ? ' (me) ' : '';?>
                </li>
                <?php endif;?>
                <?php if($amount>0):?>
                <li class="d-flex justify-content-center align-items-center">
                <div class="text-end p-1 w-100 <?php echo ($user == $full_name) ? 'fw-bold' : '';?>"><?=$full_name?><?php echo ($user == $full_name) ? ' (me) ' : '';?></div>
                <div class="progress-bar bg-success rounded-end" role="progressbar" style="width: 100%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"><text class="text-start ms-2 <?php echo ($user == $full_name) ? 'fw-bold' : '';?>"><?=$amount?><i class="bi bi-currency-euro"></i></text></div>
                </li>
                <?php endif;?>
                <?php endif;?>    
                <?php endforeach; ?>
            </ul>       
        </div>        


        
    </body>
</html>

