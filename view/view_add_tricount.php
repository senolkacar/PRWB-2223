<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Add Tricount</title>
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <script src="lib/jquery-3.6.4.min.js" type="text/javascript"></script>
    <script>
        let userID, title, description, errTitle, errDescription;

        $(function() {
            userID = '<?php echo $user->id; ?>';
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
                        'title': $("#title").val()
                    }).then(function(data) {
                        return data;
                    });
                    updateView();
                    return res;
                }
            });

            function updateView(){
                if (errTitle.text() == "") {
                    $("#errTitle").html("");
                    $("#successTitle").show();
                    $("#title").attr("class", "form-control is-valid");
                } else {
                    $("#successTitle").hide();
                    $("#errTitle").html(errTitle.text());
                    $("#title").attr("class", "form-control is-invalid");

                }
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
            });

        });
    </script>
</head>

<body>
    <header>
        <div class="container p-3 mb-3 text-dark" style="background-color: #E3F2FD;">
            <div class="d-flex justify-content-between mb-3">
                <a href="tricount/index" class="btn btn-outline-danger"> Cancel </a>
                <div class="text-secondary fw-bold mt-2">Tricount &#32; <i class="bi bi-caret-right-fill"></i> &#32; Add </div>
                <div> <button type="submit" class="btn btn-primary" form="form1">Save</button> </div>
            </div>
        </div>
    </header>

    <div class="container-sm">
        <form method='post' action='tricount/add_tricount' enctype='multipart/form-data' id="form1">
            <div class="mb-3 mt-3">
                <label for='title'> Title : </label>
                <input type="text" class="form-control" name='title' id='title' value="<?= $title; ?>">
                <div id="jsTitleError">
                    <span class="text-danger" id="errTitle"> </span>
                    <span class="text-success" id="successTitle" style="display: none;">Looks good!</span>
                </div>
            </div>

            <?php if (count($errors_title) != 0) : ?>
                <div class='errors'>
                    <ul>
                        <?php foreach ($errors_title as $error_title) : ?>
                            <li class="text-danger" id="error_title"><?= $error_title ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="mb-3 mt-3">
                <label for='description'> Descripton (optional) : </label>
                <textarea class="form-control" name='description' id='description' rows='3'><?= $description; ?></textarea> <br>
                <div id="jsDescriptionError">
                    <span class="text-danger" id="errDescription"> </span>
                    <span class="text-success" id="successDescription" style="display: none;">Looks good!</span>
                </div>
            </div>

            <?php if (count($errors_description) != 0) : ?>
                <div class='errors'>
                    <ul>
                        <?php foreach ($errors_description as $error_description) : ?>
                            <li class="text-danger" id="error_description"><?= $error_description ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </form>
    </div>
    <br>
</body>

</html>