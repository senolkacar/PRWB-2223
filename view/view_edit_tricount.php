<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Edit Tricount</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
	integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link href="css/edit_tricount_style.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
   
    </head>
    <body>

        <header>
            <div  class="container p-3 mb-3 text-dark" style="background-color: #E3F2FD;">
                <div class="d-flex justify-content-between mb-3">   
                    <a class="navbar-brand" href="tricount/index">
                    <button type="button" class="btn btn-outline-danger">Back</button>
                    </a>
                    <div class="text-secondary fw-bold mt-2"><?=$tricount->title?> &#32<i class="bi bi-caret-right-fill"></i> &#32 Edit </div>
                    <div ><button type='submit' class="btn btn-primary" form ="form1"> Save</button></div>
                </div>
            </div>
        </header>

        <div class="container-sm">
            <form method='post' action='tricount/edit_tricount/<?=$tricount->id; ?>' enctype='multipart/form-data' id ="form1">
               <h2>Settings</h2>
               <div class="mb-3 mt-3">
                    <label for='title'> Title : </label>
                    <textarea  class="form-control" name='title'  id='title' rows='1' ><?= $title; ?></textarea> 
               </div>

                <?php if (count($errors_title) != 0): ?>
                    <div class='errors'>
                        <ul>
                            <?php foreach ($errors_title as $error_title): ?>
                                <li class="text-danger"><?= $error_title ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>


               <div class="mb-3 mt-3">
                    <label for='description'> Descripton (optional) :  </label>
                    <textarea class="form-control" name='description' id='description'  rows='2' ><?= $description; ?></textarea> 
               </div>

               <?php if (count($errors_description) != 0): ?>
                    <div class='errors'>
                        <ul>
                            <?php foreach ($errors_description as $error_description): ?>
                                <li class="text-danger"><?= $error_description ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </form>


            <form method='post' action='tricount/delete_subsription/<?=$tricount->id; ?>' enctype='multipart/form-data' id ="form2">
                <h2>Subscriptions</h2>                    
                <ul class="list-group" >
                        <?php foreach ($subscriptions as $subscription): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center"><?= $subscription->full_name ?>
                            <?php if($subscription->full_name == $tricount->creator->full_name): ?>
                                (creator)                                                      
                                <?php elseif(!($subscription->has_operation()) && !($subscription->is_initiator())): ?>
                                    <input type='text' name='delete_member' value='<?= $subscription->id ?>' hidden>
                                    
                                    <button type='submit'  class="btn_delete"><span class="badge bg-white text-dark"><i class="bi bi-trash"></i> </span> </button>                                   
                                   
                            <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                </ul> 
            </form>                   
                
            <br>

            <div class="container-sm">
            <?php if(count($other_users)!=0): ?>
                <form method='post' action='tricount/add_subsription/<?=$tricount->id; ?>' enctype='multipart/form-data' id ="form3">
                    <div class="input-group">              
                        <select class="form-select" aria-label="Example select with button addon"  name = "subscriber" id="subscriber" value="add subscriber" required>
                            <option value="">--Add a new subscriber--</option>
                            <?php foreach ($other_users as $other_user): ?>  
                            <option value="<?=$other_user->full_name; ?>"><?=$other_user->full_name; ?></option> 
                            <?php endforeach; ?>
                         </select>                                                                               
                        <button class="btn btn-primary" style="width: auto" type='submit'>add</button>                                                                  
                    </div>               
                </form>
            <?php endif; ?>
            </div>

            <br>
            <div class="container-sm">            
            <div class="text-danger"><?= $error; ?> </div>      
            
            <br>
            

        </div>
        <br><br>                    
        <footer class="footer mt-auto">   
            <div class="container-sm">                
                <form class='link' action='tricount/delete/<?=$tricount->id; ?>' method='post' >
                <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-danger">Delete this tricount</button>
                    </div>
                </form>
            </div>
        </footer >


       
    </body>
</html>