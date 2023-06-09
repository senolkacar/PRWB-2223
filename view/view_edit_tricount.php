<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Edit Tricount</title>
        <base href="<?= $web_root ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
	integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
        <link rel="stylesheet" href="css/edit_tricount_style.css" type="text/css">
        <script src="lib/jquery-3.6.4.min.js" type="text/javascript"></script>
        <script src="lib/sweetalert2@11.js" type="text/javascript"></script>
        <script src="lib/just-validate-4.2.0.production.min.js" type="text/javascript"></script>
        <script>
            let title, description, errTitle, errDescription;
            let userID = <?= $user->id ?>;
            <?php if ($justvalidate) { ?>
                let titleExists = false;

                function debounce(fn, time) {
                    var timer;
                    return function() {
                        clearTimeout(timer);
                        timer = setTimeout(() => {
                            fn.apply(this, arguments);
                        }, time);
                    }
                }

                $(function() {
                    const validation = new JustValidate('#settings-form', {
                        validateBeforeSubmitting: true,
                        lockForm: false,
                        focusInvalidField: false,
                        errorFieldCssClass: 'is-invalid',
                        successFieldCssClass: 'is-valid',
                        successLabelCssClass: "text-success",
                        errorLabelCssClass: "text-danger",
                    });
                    validation
                        .addField('#title', [{
                                rule: 'required',
                                errorMessage: 'Title is required'
                            },
                            {
                                rule: 'minLength',
                                value: 3,
                                errorMessage: 'Title must be at least 3 characters'
                            },
                        ], {
                            successMessage: "Looks good!",
                        })
                        .addField('#description', [{
                            rule: 'minLength',
                            value: 3,
                            errorMessage: 'If description is not empty, it must contain at least 3 characters'
                        }, ], {
                            successMessage: "Looks good!"
                        })
                    validation.onValidate(debounce(async function(event) {
                        titleExists = await $.post("tricount/tricount_exists_service/", {
                            'creator': userID,
                            'title': $("#title").val(),
                            'mode': 'edit',
                            'tricount': <?= $tricount->id ?>
                        }).then(function(data) {
                            return (data.trim() === "true");
                        });

                        if (titleExists) {
                            this.showErrors({
                                '#title': 'Title already exists for this user'
                            });
                        }
                    }, 300));

                    validation.onSuccess(function(event) {
                        if (!titleExists) {
                            event.target.submit();
                        }
                    });

                    $("input:text:first").focus;
                });
            <?php } else { ?>
                $(function() {
                    title = $("#title");
                    errTitle = $("#errTitle");
                    errDescription = $("#errDescription");
                    $("#title").blur(function() {
                        errTitle.val("");
                        if (!(/(\s*\w\s*){3}/).test($("#title").val())) {
                            errTitle.text("Title must be at least 3 characters");
                            updateView();
                        } else {
                            check_tricount_exists().then(function(data) {
                                if (data.trim() === "true") {
                                    errTitle.text("Title already exists for this user");
                                    updateView();
                                } else {
                                    errTitle.text("");
                                    updateView();
                                }
                            });
                        }

                        async function check_tricount_exists() {
                            let res = await $.post("tricount/tricount_exists_service/", {
                                'creator': userID,
                                'title': $("#title").val(),
                                'mode':'edit',
                                'tricount': <?= $tricount->id ?>
                            }).then(function(data) {
                                return data;
                            });
                            updateView();
                            return res;
                        }
                    });

                    function updateView() {
                        if (errTitle.text() == "") {
                            $("#errTitle").html("");
                            $("#successTitle").show();
                            $("#title").attr("class", "form-control is-valid");
                        } else {
                            $("#successTitle").hide();
                            $("#errTitle").html(errTitle.text());
                            $("#title").attr("class", "form-control is-invalid");

                        }
                        setDisableButton();
                    }

                    $("#description").blur(function() {
                        errDescription.val("");
                        if ($("#description").val().length > 0 && !(/(\s*\w\s*){3}/).test($("#description").val())) {
                            errDescription.val("If description is not empty, it must contain at least 3 characters");
                        }

                        if (errDescription.val() == "") {
                            $("#errDescription").html("");
                            $("#successDescription").show();
                            $("#description").attr("class", "form-control is-valid");
                        } else {
                            $("#successDescription").hide();
                            $("#errDescription").html(errDescription.val());
                            $("#description").attr("class", "form-control is-invalid");
                        }  
                        setDisableButton();
                    });

                    function setDisableButton() {
                        if ($("#description").hasClass("form-control is-invalid") || $("#title").hasClass("form-control is-invalid")) {
                            $("#save-button").prop("disabled", true);
                        } else {
                            $("#save-button").prop("disabled", false);
                        }
                    }
            
                });
            <?php } ?>

            const tricountId = <?= $tricount->id ?>;
            let subscribers = <?=$subscribers_json ?>;
            let subscribersList;
            let sortColumn = 'full_name';
            let otherUsersList;
            let otherUsers = <?=$other_users_json ?>;
            let formChanged = false; 
            
            $(function(){
                
                subscribersList = $('#participant-list');
                subscribersList.html("<li>loading ...</li>");
                getSubscribers(); 

                otherUsersList = $('#other_users_list');
                otherUsersList.html("loading ...");
                getOtherUsers();   
                
                $('#delete-tricount').on('click', function() {                     
                    event.preventDefault();
                    //var form = this;
                    Swal.fire({
                        title: 'Are you sure?',
                        html: 
                            'Do you really want to delete tricount <b> "<?=$tricount->title?>" </b> and all of its dependencies ?' +
                            '<br>' +
                            'This process cannot be undone.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        //reverseButtons: true//swap position of buttons
                    }).then((result) => {
                        if (result.isConfirmed) {
                            deleteTricount();                   
                        }
                    });
                });

                $('textarea').on('change', function() {// or 'input'
                    formChanged = true;
                });

               /* $('#save-button').on('click', function() {
                    var isDisabled = $("#save-button").prop("disabled");                   
                    if (!isDisabled){
                        $("#settings-form").submit();
                        <?php if (count($errors) == 0): ?>
                            formChanged = false;
                            console.log(" formChanged " +   <?php echo count($errors); ?> );
                        <?php endif; ?>   
                    } else {
                        formChanged = true;                        
                    }
                });
                */

                $('#back-button').on('click', function(e) {                    
                    if (formChanged) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Unsaved changes !',
                            text: 'Are you sure you want to leave this form ? Changes you made will not be saved.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Leave Page',
                            cancelButtonText: 'Cancel',
                            confirmButtonColor: '#d33',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = $(this).attr('href');
                            }
                        });
                    }
                });


            });

            async function deleteTricount() {
                try {
                    await $.post("tricount/delete_tricount_service/" + tricountId, null);
                   
                    await handleDeleteSuccess();
                } catch (e) {
                    // Handle error if needed
                }
            }

            async function handleDeleteSuccess() {
                await Swal.fire({
                    title: 'Deleted!',
                    text: 'This tricount has been deleted.',
                    icon: 'success',
                    showCancelButton: false,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3085d6',
                });
                window.location.href = "tricount/index";
            }


            async function getSubscribers(){

                try {
                    subscribers = await $.getJSON("tricount/get_tricount_subscrier_service/" + tricountId);
                    sortSubscribers();
                    displaySubscribers();
                } catch(e) {
                    subscribersList.html("<li>1 Error encountered while retrieving the subscribers!</li>");
                }
            }

            async function getOtherUsers(){

                try {
                    otherUsers = await $.getJSON("tricount/get_user_not_tricount_subscrier_service/" + tricountId);                    
                    sortOtherUsers(); 
                    displayOtherUsers();
                } catch(e) {
                    otherUsersList.html(" Error encountered while retrieving other users!");  
                }
            }

            async function deleteSubscriber(id){ 
                
                const idx = subscribers.findIndex(function (el, idx, arr) {                    
                    return el.id === id;
                });

                subscribers.splice(idx, 1);           
                
                try {
                   // console.log("delete id " + id );
                    const res=await $.post("tricount/delete_subscription_service/" + tricountId, {"delete_member": id}, null, 'json');       
                    //console.log(res === true);//without ", null, 'json'" res="true" but not boolean
                    await handleDeleteSubscriberSuccess();
                    getSubscribers();
                    sortSubscribers()
                    displaySubscribers();
                    getOtherUsers();
                    sortOtherUsers();
                    displayOtherUsers();            
   
                } catch(e) {
                    subscribersList.html(" Error encountered while deleting the subscriber!");
                }
            }

            async function handleDeleteSubscriberSuccess() {
                await Swal.fire({
                    title: 'Deleted!',
                    text: 'This subscriber has been deleted.',
                    icon: 'success',
                    showCancelButton: false,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3085d6',
                });
            }

            async function addSubscriber(id){ 
                
                const idx = otherUsers.findIndex(function (el, idx, arr) {                    
                    return el.id === id;
                });

                otherUsers.splice(idx, 1);           
                
                try {
                    await $.post("tricount/add_subscription_service/" + tricountId, {"subscriber": id});              
                    getOtherUsers();
                    sortOtherUsers();
                    displayOtherUsers();
                    getSubscribers();
                    sortSubscribers();
                    displaySubscribers();
                } catch(e) {
                    otherUsersList.html("Error encountered while adding the subscriber!");
                }
            }

            function sortSubscribers() {
                subscribers.sort(function(a, b) {
                    return a[sortColumn] - b[sortColumn];                    
                });
            }

            function sortOtherUsers() {
                otherUsers.sort(function(a, b) {
                    return a[sortColumn] - b[sortColumn]; 
                });
            }

            function showDeleteSbscriberConfirmation(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you really want to delete this subscriber?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    //reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        deleteSubscriber(id);
                    }
                });
            }

            function displaySubscribers() {
                let html ="";
                for (let s of subscribers) {
                    html += '<li class="list-group-item d-flex justify-content-between align-items-center">';
		            html += s.full_name;
                    html +=  (s.is_creator ? "(creator)" : "");
                    html +=  (!(s.has_operation ||s.is_creator||s.is_initiator) ? "<a href='javascript:showDeleteSbscriberConfirmation(" + s.id + ")'><i class='bi bi-trash'></i></a>" : "") ; 
                    html += "</li>";
                }
                subscribersList.html(html);
            }

            function displayOtherUsers() {
                let html = "<div class='input-group'>";
                html +="<select class='form-select' id='other-users-select'>";
                html += '<option value="">--Add a new subscriber--</option>';
                for (let o of otherUsers) {
                    html += '<option value="' + o.id + '">';
		            html += o.full_name;
                    html += "</option>"
                }

                html += "</select>";
                html += "<button type='button' id='add-btn' class='btn btn-primary' style='width: auto'>add</button> ";
                html += "</div>";

                otherUsersList.html(html);

                $('#add-btn').click(function() {
                    const selectedId = $('#other-users-select').val();
                    addSubscriber(selectedId);
                });              

                if ($('#other-users-select option').length === 1) {
                    $('#other-users-select').hide();
                    $('#add-btn').hide();

                }

            }
        
        </script>
   
    </head>
    <body>

        <header>
            <div  class="container p-3 mb-3 text-dark" style="background-color: #E3F2FD;">
                <div class="d-flex justify-content-between mb-3">   
                    <a href="tricount/index" id="back-button" class="btn btn-outline-danger"> Back </a>
                    <div class="text-secondary fw-bold mt-2"><?=$tricount->title?> &#32; <i class="bi bi-caret-right-fill"></i> &#32; Edit </div>
                    <div ><button type='submit' id="save-button" class="btn btn-primary" form ="settings-form"> Save</button></div>
                </div>
            </div>
        </header>

        <div class="container-sm">
            <form method='post' action='tricount/edit_tricount/<?=$tricount->id; ?>' enctype='multipart/form-data' id ="settings-form">
               <h2>Settings</h2>
               <div class="mb-3 mt-3 has-validation">
                    <label for='title'> Title : </label>
                    <textarea class="form-control <?php echo count($errors_title)!=0 ? 'is-invalid' : ''?>" name='title'  id='title' rows='1' ><?= $title; ?></textarea> 
                    <div id="jsTitleError" style="<?php $justvalidate ? "display: none;" : "" ?>">
                    <span class="text-danger" id="errTitle"> </span>
                    <span class="text-success" id="successTitle" style="display: none;">Looks good!</span>
                </div>
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
                    <textarea class="form-control <?php echo count($errors_description)!=0 ? 'is-invalid' : ''?>" name='description' id='description'  rows='2' ><?= $description; ?></textarea> 
                    <div id="jsDescriptionError" style="<?php $justvalidate ? "display: none;" : "" ?>">
                    <span class="text-danger" id="errDescription"> </span>
                    <span class="text-success" id="successDescription" style="display: none;">Looks good!</span>
                </div>
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
           
                <h2>Subscriptions</h2>                    
                <ul class="list-group" id="participant-list">
                    <?php $subscriptions = $tricount->get_users_including_creator(); ?>
                        <?php foreach ($subscriptions as $subscription): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center"><?= $subscription->full_name ?>
                            <?php if($subscription->id == $tricount->creator->id): ?>
                                (creator)                                                      
                                <?php elseif(!($subscription->has_operation($tricount)) && !($subscription->is_initiator($tricount))): ?>
                                    <form method='post' action='tricount/delete_subscription/<?=$tricount->id; ?>' enctype='multipart/form-data' id ="form2">
                                    <input type='text' name='delete_member' value='<?= $subscription->id ?>' hidden>                                    
                                    <button type='submit'  class="btn_delete" data-participant-id="<?= $subscription->id ?>"><span class="badge bg-white text-dark"><i class="bi bi-trash"></i> </span> </button>  
                                    </form> 
                                   
                            <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                </ul>                           
                
            <br>

            <div class="container-sm" id = "other_users_list">
            <?php $other_users = $tricount->get_users_not_subscriber(); ?>
            <?php if(count($other_users)!=0): ?>
                <form method='post' action='tricount/add_subscription/<?=$tricount->id; ?>' enctype='multipart/form-data' id ="form3">
                    <div class="input-group">              
                        <select class="form-select" name = "subscriber" id="subscriber" required>
                            <option value="">--Add a new subscriber--</option>
                            <?php foreach ($other_users as $other_user): ?>  
                            <option value="<?=$other_user->id; ?>"><?=$other_user->full_name; ?></option>                             
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
            </div>              
            <br>            

        </div>
        <br><br>                    
        <footer class="footer mt-auto">   
            <div class="container-sm">                
            <a href='tricount/delete/<?=$tricount->id; ?>' id="delete-tricount" class="btn btn-danger w-100"> Delete this tricount </a>
            </div>
        </footer >
       
    </body>
</html>