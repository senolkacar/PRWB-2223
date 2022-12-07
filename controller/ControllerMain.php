<?php
require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerMain extends Controller{

    public function index(): void{
        if($this->user_logged()){
            $this->redirect("tricount","index");
        }else{
            $mail ='';
            $password = '';
            $errors = [];
            (new View("login"))->show(array("mail"=>$mail,"password"=>$password,"errors"=>$errors));
        }

    }

    public function login() : void{
        $mail ='';
        $password = '';
        $errors = [];
        if(isset($_POST["mail"])&&isset($_POST["password"])){
            $mail = $_POST["mail"];
            $password = $_POST["password"];
            $errors = User::validate_login($mail,$password);
            if(empty($errors)){
                $this->log_user(User::get_user_by_mail($mail));
            }
        }
        (new View("login"))->show(array("mail"=>$mail,"password"=>$password,"errors"=>$errors));
    }

    public function signup(){
        $mail ='';
        $full_name = '';
        $iban = '';
        $password = '';
        $confirm_password = '';
        $errors = [];
        if(isset($_POST["mail"])&&isset($_POST["full_name"])&&isset($_POST["password"])&&isset($_POST["confirm_password"])){
            if(isset($_POST["iban"])){
                $iban = $_POST["iban"];
            }
            $mail = $_POST["mail"];
            $full_name = $_POST["full_name"];
            $password = $_POST["password"];
            $confirm_password = $_POST["confirm_password"];
            //role definit par défaut à "user"
            $user = new User($mail,Tools::my_hash($password),$full_name,"user",$iban);
            $errors = User::validate_unicity($mail);
            $errors = array_merge($errors,User::validate_full_name($full_name));
            $errors = array_merge($errors,User::validate_password($password));
            $errors = array_merge($errors,User::validate_passwords($password,$confirm_password));
            $errors = array_merge($errors,User::validate_iban($iban));
            $errors = array_merge($errors,User::validate_email($mail));

            if(count($errors)==0){
                $user->persist();
                $this->log_user($user);
            }
        }
        (new View("signup"))->show(array("mail"=>$mail,"full_name"=>$full_name,"iban"=>$iban,"password"=>$password,"confirm_password"=>$confirm_password,"errors"=>$errors));
        
    }
}

?>