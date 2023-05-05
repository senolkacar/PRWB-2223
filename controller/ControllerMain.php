<?php
require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'controller/MyController.php';

class ControllerMain extends MyController{

    public function email_exists_service(): void{
       if(isset($_POST["mail"])){
           $errors = [];
            $errors= $this->validate_email_format($_POST["mail"]);
           if(count($errors)==0){
               $mail = $_POST["mail"];
               $user = User::get_user_by_mail($mail);
               if($user){
                  echo "true";
               }else{
                  echo "false";
               }
        }else{
            $this->redirect("main","index");
    }

    }else{
        $this->redirect("main","index");
    }
}

    public function fname_exists_service(): void{
        if(isset($_POST["full_name"])){
            $errors = [];
            $errors= $this->validate_full_name_format($_POST["full_name"]);
            if(count($errors)==0){
                $full_name = $_POST["full_name"];
                $user = User::get_user_by_name($full_name);
                if($user){
                   echo "true";
                }else{
                   echo "false";
                }
            }else{
                $this->redirect("main","index");
            }
            
     }else{
            $this->redirect("main","index");
        }
    }

    public function index(): void{
        if($this->user_logged()){
            $this->redirect("tricount","index");
        }else{
            $justvalidate = $this->isJustValidateOn();
            $mail ='';
            $password = '';
            $errors = [];
            (new View("login"))->show(array("mail"=>$mail,"password"=>$password,"errors"=>$errors,"justvalidate"=>$justvalidate));
        }

    }

    public function login() : void{
        $mail ='';
        $password = '';
        $errors = [];
        $justvalidate = $this->isJustValidateOn();
        $user =$this->get_user_or_false();
        if($user){
            $this->redirect("tricount");
        } else{
            if(isset($_POST["mail"])&&isset($_POST["password"])){
                $mail = $_POST["mail"];
                $password = $_POST["password"];
                $errors = $this->validate_login($mail,$password);
                if(empty($errors)){
                    $this->log_user(User::get_user_by_mail($mail));
                }
            }
            (new View("login"))->show(array("mail"=>$mail,"password"=>$password,"errors"=>$errors,"justvalidate"=>$justvalidate));

        }
        
    }

    public function signup(){
        $mail ='';
        $full_name = '';
        $justvalidate = $this->isJustValidateOn();
        $iban = null;
        $password = '';
        $password_confirm = '';
        $errors = [];
        $errors_email = [];
        $errors_full_name = [];
        $errors_iban = [];
        $errors_password = [];
        $errors_password_confirm = [];
        $user =$this->get_user_or_false();
        if($user){
            $this->redirect("tricount");
        } else {
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
                "justvalidate"=>$justvalidate,
                "errors"=>$errors,
                "errors_email"=>$errors_email,
                "errors_full_name"=>$errors_full_name,
                "errors_iban"=>$errors_iban,
                "errors_password"=>$errors_password,
                "errors_password_confirm"=>$errors_password_confirm
            ));
            

        }
        
    }
}

?>