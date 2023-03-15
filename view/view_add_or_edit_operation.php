<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?=$page_title?></title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
	integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
        <script src="lib/jquery-3.6.4.min.js" type="text/javascript"></script>
        <script>
            
            let totalAmount, totalWeight;
            $(function(){
                totalAmount = parseFloat($('#amount').val());
                console.log("js total amount "+parseFloat($('#amount').val()));
                weight = getTotalWeight();               

                var ratios = getRatio();

                var $amounts = $('input[name="amount[]"]');

                    $amounts.each(function(index) {
                        var ratio = ratios[index];
                        var value = ratio.toFixed(2);
                        $(this).val(value);
                    });        

                $('input[name="checkboxes[]"]').on('change', function() {
                    var checkboxes = $('input[name="checkboxes[]"]');
                    var weights = $('input[name="weights[]"]');
                    for (var i = 0; i < checkboxes.length; i++) {
                        if (!$(checkboxes[i]).prop('checked')) {
                            $(weights[i]).val(0);
                        }
                    }
                    weight = getTotalWeight();               

                    var ratios = getRatio();

                    $amounts.each(function(index) {
                        var ratio = ratios[index];
                        var value = ratio.toFixed(2);
                        $(this).val(value);
                    });  

                });

                $('input[name="weights[]"]').on('change', function() {
                    var checkboxes = $('input[name="checkboxes[]"]');
                    var weights = $('input[name="weights[]"]');
                    for (var i = 0; i < checkboxes.length; i++) {
                        if ($(weights[i]).val() == 0) {
                            $(checkboxes[i]).prop('checked', false);
                        }
                    }

                    weight = getTotalWeight();               

                    var ratios = getRatio();

                    $amounts.each(function(index) {
                        var ratio = ratios[index];
                        var value = ratio.toFixed(2);
                        $(this).val(value);
                    });  

                });   

            
             });   

             function getTotalWeight() {
                totalWeight = 0;
                $('input[name="weights[]"]').each(function() {
                    totalWeight += parseFloat($(this).val());
                });

                console.log("js total weight " +totalWeight );
                return totalWeight;
             }    

             function getRatio() {
                var weightValues = $('input[name="weights[]"]').map(function(){
                    return parseFloat($(this).val());
                }).get();
                 console.log("js total weight array" + weightValues );

                function calculateRatio(weight) {
                    return totalAmount/totalWeight*weight;
                }

                var ratios = $.map(weightValues, calculateRatio);
                    return ratios;

             }   
            
        </script>
    </head>
    <body>
    <header>
        <div class="container p-3 mb-3 text-dark" style="background-color:#E3F2FD">
            <div class="d-flex justify-content-between">
            <?php if($operation_name=="add"){?>
            <a class="btn btn-outline-danger" href="tricount/show_tricount/<?=$tricount->id;?>">Back</a>
            <?php }else{?>
            <a class="btn btn-outline-danger" href="operation/show_operation/<?=$operation->id;?>">Back</a>
            <?php }; ?> 
            <div class="text-secondary fw-bold mt-2"><?=$tricount->title?> &#32;<i class="bi bi-caret-right-fill"></i> &#32; Expenses </div>
            <button type="submit" class="btn btn-primary" form="form1">Save</button>
            </div>
        </div>
        </header>
        <div class="container-sm">
            <?php if($operation_name=="add"){
                $action = "operation/add_operation/$tricount->id";
            }else{
                $action = "operation/edit_operation/$operation->id";}
            ?>
            <div class="form-group">
            <form method='post' action=<?=$action?> enctype='multipart/form-data' id="form1">
            <div class="input-group mb-3 has-validation">    
                <input type="text" class="form-control<?php echo count($errors_title)!=0 ? ' is-invalid' : ''?>" name='title' id='title' placeholder="Title" value="<?=$title?>">
            </div>
                <?php if (count($errors_title) != 0): ?>
                <div class='errors'>
                    <ul>
                        <?php foreach ($errors_title as $error): ?>
                            <li class="text-danger"><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
                <div class="input-group mb-3">
                <input type="number" class="form-control<?php echo count($errors_amount)!=0 ? ' is-invalid' : ''?>" step="0.01" name='amount' id='amount' value="<?=$amount?>" placeholder="Amount" 
                onchange="console.log('html total amount ' + this.value);" >
                <span class="input-group-text">EUR</span>            
                </div>
                <?php if (count($errors_amount) != 0): ?>
                <div class='errors'>
                    <ul>
                        <?php foreach ($errors_amount as $error_amount): ?>
                            <li class="text-danger"><?= $error_amount ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
                <label for="date">Date</label>
                <input type="date" class="form-control mt-2 mb-2" id="date" name="date" required value="<?=$date?>">
                <?php if (count($errors_date) != 0): ?>
                <div class='errors'>
                    <ul>
                        <?php foreach ($errors_date as $error_date): ?>
                            <li class="text-danger"><?= $error_date ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
                <label for="payer">Paid by:</label>
                            <select class="form-control mt-2" name="payer" id="payer">
                            <?php foreach ($subscriptions as $subscription): ?>  
                            <option value="<?=$subscription->id; ?>" <?php echo $payer!=null && $subscription->id == $payer->id ? "selected" : ""?> ><?=$subscription->full_name; ?></option>  
                            <?php endforeach; ?>
                            </select>                           
                <p class="mt-2">For whom ?(select at least one)</p>

                    <?php foreach ($subscriptions as $subscription): ?>
                        <div class='input-group input-group-lg'>  
                        <div class="input-group-text mb-3">
                        <input type="checkbox" id="checkboxes" class="form-check-input" name="checkboxes[]" value="<?=$subscription->id?>"
                            <?php if (in_array($subscription->id, $checkboxes)) { echo 'checked'; } ?>>
                        </div>
                        <div class="input-group-text mb-3 w-50">
                        <span class="text"><?=$subscription->full_name;?></span>
                        </div>
                        <?php $weight = 0; ?>
                        <?php for($i=0; $i<count($weights); $i++): ?>
                            <?php if($ids[$i]==$subscription->id): ?>
                                <?php $weight = $weights[$i]; ?>
                            <?php endif; ?>
                        <?php endfor; ?>
                        <input type="number" step="0.01" id="subscription-amount" class="form-control mb-3" name ="amount[]" value= "0" readonly>
                        <input type="number" class="form-control mb-3" id="weights" name="weights[]" min="0" max="<?=$nb_subscriptions?>" value="<?=$weight?>" onchange="console.log('html weight=' + this.value);">
                        <input type="hidden" name="ids[]" value="<?=$subscription->id?>">
                        </div>
                    <?php endforeach; ?>                 
                    <?php if(count($errors_checkbox)!=0) : ?>
                            <div class='errors'>
                                <ul>
                                    <?php foreach ($errors_checkbox as $error): ?>
                                        <li class="text-danger"><?= $error ?></li>
                                    <?php endforeach; ?>
                                    </ul>
                            </div>
                        <?php endif; ?>
                    <?php if(count($errors_weights)!=0) : ?>
                            <div class='errors'>
                                <ul>
                                    <?php foreach ($errors_weights as $error): ?>
                                        <li class="text-danger"><?= $error ?></li>
                                    <?php endforeach; ?>
                                    </ul>
                            </div>
                        <?php endif; ?>               
            </form>
        </div>
        <?php if($operation_name=="edit"){?>
            <footer class="footer mt-auto w-100">   
                <a class="btn btn-danger w-100" href="operation/delete_operation/<?=$operation->id;?>">Delete</a>
            </footer>
            <?php }; ?>
        </div>
    </body>
</html>