<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>your tricounts</title>
        <base href="<?= $web_root ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
	integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
      
    </head>
    <body>

    <header>
        <div  class="container p-3 mb-3 text-dark" style="background-color: #E3F2FD;">
            <div class="d-flex justify-content-between mb-3">   
                <a href="tricount/index" class="btn btn-outline-danger">Back</a>
                <div class="h4">Settings</div>
            </div>
        </div>
    </header>

    <div class="container-sm">
        <p>Hey <b><?= $user->full_name ?></b></p>
        <p>I know your email address is <span class="text-danger"><?= $user->mail ?>.</span></p>
        <p>What can I do for you?</p>  
    </div> 

   

        <footer class="footer mt-auto fixed-bottom">
            <div class="container-sm">
                <div class="position-relative">
                    <div class="position-absolute bottom-0 start-0"></div>
                    <a href="user/edit_profile" class="btn btn-outline-primary form-control mb-2">  
                        <p class="text">Edit profile</p> 
                    </a>
                    <a href="user/edit_password" class="btn btn-outline-primary form-control mb-2">
                        <p class="text">Change password</p>
                    </a>
                    <a href="main/logout" class="btn btn btn-danger input-block-level form-control mb-2">
                        <p class="text">logout</p>
                    </a>
                </div>                
            </div>
        </footer>
 
    </body>
</html>