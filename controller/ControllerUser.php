<?php
include_once 'framework/Controller.php';
include_once 'model/User.php';
include_once 'model/Tricount.php';
include_once 'framework/View.php';

class ControllerUser extends Controller {

    public function index() : void {
        $user=$this->get_user_or_redirect();
        $tricounts = Tricount::get_tricounts();
        (new View("list_tricount"))->show(["tricounts"=>$tricounts]);
    } 


    public function settings() : void{
        $user=$this->get_user_or_redirect();
        (new View("settings"))->show(["user"=>$user]);
    }

    public function edit_profile():void{
        //currently it doesnt check if we change the mail to another user's mail
        $user=$this->get_user_or_redirect();
        $errors = [];
        $success = "";
        if(isset($_POST["mail"])&&isset($_POST["full_name"])){
            $errors = array_merge($errors,User::validate_email($_POST["mail"]));
            $errors = array_merge($errors,User::validate_full_name($_POST["full_name"]));
            if(isset($_POST["iban"])&&strlen($_POST["iban"])>0){
                $errors = array_merge($errors,User::validate_iban($_POST["iban"]));
            }
            if(count($errors)==0){
                $user->mail = $_POST["mail"];
                $user->full_name = $_POST["full_name"];
                $user->iban = $_POST["iban"];
                $user->persist();
                $success = "Profile updated";
            }
        }

        (new View("edit_profile"))->show(["user"=>$user,"errors"=>$errors,"success"=>$success]);
    }

    public function edit_password():void{
        $user=$this->get_user_or_redirect();
        $errors = [];
        $success = "";
        if(isset($_POST["old_password"])&&isset($_POST["new_password"])&&isset($_POST["new_password_confirm"])){
            if(!password_verify($_POST["old_password"],$user->hashed_password)){
                $errors[] = "Wrong password";
            }
            if($_POST["new_password"]!=$_POST["new_password_confirm"]){
                $errors[] = "Passwords don't match";
            }
            if(count($errors)==0){
                $user->hashed_password = password_hash($_POST["new_password"],PASSWORD_DEFAULT);
                $user->persist();
                $success = "Password changed";
            }
        }
        (new View("edit_password"))->show(["user"=>$user,"errors"=>$errors,"success"=>$success]);
    }

}





?>