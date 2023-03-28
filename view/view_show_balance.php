<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Balance</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
	integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
      
    </head>
    <body>
        <header>
        <div class="container p-2 mb-3 text-dark" style="background-color:#E3F2FD">
        <div class="d-flex justify-content-between">
        <a class="btn btn-outline-danger btn-block" href="tricount/show_tricount/<?=$tricount->id?>">Back</a>
        <div class="text-secondary fw-bold mt-2"><?=$tricount->title?>&#32;<i class="bi bi-caret-right-fill"></i> &#32; Balance </div>
        </div>
        </div>
        </header>
        <div class="container-sm">
            <ul class="list-group w-100">
                <?php $balance = $tricount->get_balance_by_tricount();?>
                <?php $max = $tricount ->get_max_balance();?>
                <?php foreach($balance as $full_name => $amount): ?>
                <?php if($amount==0):?>
                    <li class="d-flex justify-content-center align-items-center">
                <div class="text-end p-1 w-50 <?php echo  ($user->full_name == $full_name) ? 'fw-bold' : '';?>"><?=$full_name?><?php echo  ($user->full_name == $full_name) ? ' (me) ' : '';?></div>
                <div class="progress w-50" style="height:28px; background-color:#ffff;">
                    <div class="progress-bar bg-success rounded-end" role="progressbar" style="width: 0%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"><div class="text-start text-dark ms-2 <?php echo ($user == $full_name) ? 'fw-bold' : '';?>" style="position:absolute"><?=$amount?><i class="bi bi-currency-euro"></i></div></div>
                </div>
                    </li>
                <?php endif;?>
                <?php if($amount<0):?>
                <li class="d-flex justify-content-center align-items-center">
                <div class="progress w-50 float-right" style="height:28px; direction:rtl; background-color:#ffff;">
                    <div class="progress-bar bg-danger rounded-start" role="progressbar" style="width: <?=(round((abs($amount)/$max*100)))?>%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"><div class="text-end text-dark me-2 <?php echo ($user == $full_name) ? 'fw-bold' : '';?>" style="direction:ltr; position:absolute;"><?=$amount?><i class="bi bi-currency-euro"></i></div></div>
                </div>
                <div class="text-start p-1 w-50 <?php echo ($user->full_name == $full_name) ? 'fw-bold' : '';?>"><?=$full_name?><?php echo  ($user->full_name == $full_name) ? ' (me) ' : '';?></div>
                </li>
                <?php endif;?>
                <?php if($amount>0):?>
                <li class="d-flex justify-content-center align-items-center">
                <div class="text-end p-1 w-50 <?php echo  ($user->full_name == $full_name)? 'fw-bold' : '';?>"><?=$full_name?><?php echo  ($user->full_name == $full_name) ? ' (me) ' : '';?></div>
                <div class="progress w-50" style="height:28px; background-color:#ffff;">
                    <div class="progress-bar bg-success rounded-end" role="progressbar" style="width: <?=round((abs($amount)/$max*100))?>%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"><div class="text-start text-dark ms-2 <?php echo ($user == $full_name) ? 'fw-bold' : '';?>" style="position:absolute"><?=$amount?><i class="bi bi-currency-euro"></i></div></div>
                </div>
                </li>
                <?php endif;?>  
                <?php endforeach; ?>
            </ul>       
        </div>        


        
    </body>
</html>

