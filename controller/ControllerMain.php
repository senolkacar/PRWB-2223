<?php
require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'controller/MyController.php';

class ControllerMain extends MyController{

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
            $errors = $this->validate_login($mail,$password);
            if(empty($errors)){
                $this->log_user(User::get_user_by_mail($mail));
            }
        }
        (new View("login"))->show(array("mail"=>$mail,"password"=>$password,"errors"=>$errors));
    }

    public function signup(){
        $mail ='';
        $full_name = '';
        $iban = null;
        $password = '';
        $password_confirm = '';
        $errors = [];
        $errors_email = [];
        $errors_full_name = [];
        $errors_iban = [];
        $errors_password = [];
        $errors_password_confirm = [];
        if(isset($_POST["mail"])&&isset($_POST["full_name"])&&isset($_POST["password"])&&isset($_POST["password_confirm"])){
            if(isset($_POST["iban"])){
                $iban = $_POST["iban"];
            }
            $mail = $_POST["mail"];
            $full_name = $_POST["full_name"];
            $password = $_POST["password"];
            $password_confirm = $_POST["password_confirm"];
            //role definit par défaut à "user"
            $user = new User($mail,Tools::my_hash($password),$full_name,"user",$iban);
            $errors_email = $this->validate_email($mail);
            $errors_full_name = $this->validate_full_name($full_name);
            $errors_iban = $this->validate_iban($iban);
            $errors_password = $this->validate_password($password);
            $errors_password_confirm = $this->validate_passwords($password,$password_confirm);

            $errors = (array_merge($errors_email,$errors_full_name,$errors_iban,$errors_password,$errors_password_confirm));

            if(count($errors)==0){
                $user->persist();// change to persisit by id ?
                $this->log_user($user);
            }
        }
        (new View("signup"))->show(array(
            "mail"=>$mail,
            "full_name"=>$full_name,
            "iban"=>$iban,
            "password"=>$password,
            "password_confirm"=>$password_confirm,
            "errors"=>$errors,
            "errors_email"=>$errors_email,
            "errors_full_name"=>$errors_full_name,
            "errors_iban"=>$errors_iban,
            "errors_password"=>$errors_password,
            "errors_password_confirm"=>$errors_password_confirm
        ));
        
    }
}

?>