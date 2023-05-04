<?php
include_once 'framework/Controller.php';
include_once 'model/User.php';
include_once 'model/Tricount.php';
include_once 'framework/View.php';
include_once 'controller/MyController.php';

class ControllerUser extends MyController {

    public function index() : void {
        $user=$this->get_user_or_redirect();
        $tricounts =$user->get_tricounts_involved();
        (new View("list_tricount"))->show(["tricounts"=>$tricounts]);
    } 


    public function settings() : void{
        $user=$this->get_user_or_redirect();
        (new View("settings"))->show(["user"=>$user]);
    }


    public function name_available_service() : void {
        $res = "true";
        $user_connected = $this->get_user_or_false();//false ?
       
        if(isset($_GET["param1"]) && $_GET["param1"] !== ""){
            $user = User::get_user_by_name($_GET["param1"]);
            if($user && $user->id != $user_connected->id)
                $res = "false";
        }
        echo $res;
    }

    public function email_available_service() : void {
        $res = "true";
        $user_connected = $this->get_user_or_false();
       
        if(isset($_GET["param1"]) && $_GET["param1"] !== ""){
            $user = User::get_user_by_mail($_GET["param1"]);
            if($user && $user->id != $user_connected->id)
                $res = "false";
        }
        echo $res;
    }

    public function edit_profile():void{
        //currently it doesnt check if we change the mail to another user's mail
        $user=$this->get_user_or_redirect();
        $mail=$user->mail;
        $full_name=$user->full_name;
        $iban=$user->iban;
        $errors_mail=[];
        $errors_name=[];
        $errors_iban=[];
        $errors = [];
        $success = "";
        $justvalidate = $this->isJustValidateOn();

        if(isset($_POST["mail"])&&isset($_POST["full_name"])){
            $mail = $_POST["mail"];
            $full_name=$_POST["full_name"];

            $errors_mail = $this->validate_email($mail);
            $errors_name = $this->validate_full_name($full_name);

            if(isset($_POST["iban"])){
                $iban = $_POST["iban"];
                $errors_iban = $this->validate_iban($_POST["iban"]);          
            }

            $errors = (array_merge($errors_mail,$errors_name,$errors_iban));
            if(count($errors)==0){
                $user->mail = $_POST["mail"];
                $user->full_name = $_POST["full_name"];
                $user->iban = strtoupper($_POST["iban"]);
                $user->persist();
            }
        }
        if(count($_POST) > 0 && count($errors) == 0)
            $this -> redirect("User", "edit_profile", "ok");
        if (isset($_GET['param1']) && $_GET['param1'] ==="ok")
            $success = "Your profile has been successfully updated.";

        (new View("edit_profile"))->show(["user"=>$user,
                                    "mail"=>$mail,
                                    "full_name"=>$full_name,
                                    "iban"=>$iban,
                                    "errors_mail"=>$errors_mail,
                                    "errors_name"=>$errors_name,
                                    "errors_iban"=>$errors_iban,
                                    "errors"=>$errors,
                                    "justvalidate"=>$justvalidate,
                                    "success"=>$success]);
    }

    public function edit_password():void{
        $user=$this->get_user_or_redirect();
        $old_password ="";
        $new_password ="";
        $new_password_confirm ="";
        $errors = [];
        $success = "";
        if(isset($_POST["old_password"])&&isset($_POST["new_password"])&&isset($_POST["new_password_confirm"])){
            $old_password = $_POST["old_password"];           
            $new_password = $_POST["new_password"];            
            $new_password_confirm = $_POST["new_password_confirm"];            

            $errors_old_password = [];
            $errors_new_password = $this->validate_password($_POST["new_password"]);
            $errors_password_confirm = $this->validate_passwords($_POST["new_password"],$_POST["new_password_confirm"]);

            if(!$this->check_password ($_POST["old_password"],$user->hashed_password)){
                $errors_old_password[] = "Wrong old password";
            }
            if($user->hashed_password == Tools::my_hash($new_password)){
                $errors_new_password[] = "new password couldn't be the same as the old password";
            }

            $errors = (array_merge($errors_old_password,$errors_new_password,$errors_password_confirm));
            if(count($errors)==0){
                $user->hashed_password = Tools::my_hash($new_password);
                $user->persist();
            }
        }

        if(count($_POST) > 0 && count($errors) == 0)
            $this -> redirect("User", "edit_password", "ok");
        if (isset($_GET['param1']) && $_GET['param1'] ==="ok")
            $success = "Password changed";


        (new View("edit_password"))->show(["user"=>$user,
                                    "old_password"=>$old_password,
                                    "new_password"=>$new_password,
                                    "new_password_confirm"=>$new_password_confirm,
                                    "errors"=>$errors,"success"=>$success]);
    }

}





?>