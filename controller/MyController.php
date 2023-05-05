<?php
require_once 'framework/View.php';
require_once 'framework/Controller.php';

Class MyController extends Controller{
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

    public function validate_title(?string $title): array{
        $errors = [];
        if ($title == null || strlen($title) == 0 || $title == "") {
            $errors[] = "Title is mandatory";
        } elseif (strlen(trim($title)) < 3 || empty(trim($title))) {
            $errors[] = "Title must have at least 3 characters(excluding white spaces)";
        }
        return $errors;
    }

    public static function validate_email_format(string $mail):array{
        $errors=[];
        if(!strlen(trim($mail))>0){
            $errors[] = "Email is required";
        }
        elseif(!filter_var($mail,FILTER_VALIDATE_EMAIL)){
            $errors[] = "Invalid email";
        }
        return $errors;

    }

    public function validate_email(string $mail): array{ 
        $errors1=[];
        $user_connected = $this->get_user_or_false();
        $user = User::get_user_by_mail($mail);
        if($user_connected){
            if($user && $user->id != $user_connected->id){ 
                $errors1[] = "Email already used";
            }  
        }else{
            if($user){
                $errors1[] = "Email already used";
            }
        }
        $errors=(array_merge($errors1,self::validate_email_format($mail)));
        return $errors;
    }

    public function validate_password(string $password): array{
        $errors=[];
        if(strlen(trim($password))<8||strlen(trim($password))>16){
            $errors[] = "Password length must be between 8 and 16 characters";
        }
        if(!((preg_match("/[A-Z]/",$password))&&preg_match("/\d/",$password)&&preg_match("/['\";:,.\/?!\\-]/",$password))){
            $errors[] = "Password must contain at least one uppercase letter, one number and one special character";
        }
        return $errors;
    }

    public function check_password(string $clear_password, string $hash): bool{
        return $hash === Tools::my_hash($clear_password);
    }

    public function validate_passwords(string $password, string $password_confirm): array{
        //should we show the validation for each password ? 
        //only the first one is necessary. The second one should be the same as the first one(Tingting)
        //$errors=User::validate_password($password_confirm);
        $errors=[];
        if($password !== $password_confirm){
            $errors[] = "Passwords don't match";
        }
        return $errors;
    }

    public function validate_login(string $mail, string $password): array{
        $errors = [];
        $mail_error = self::validate_email_format($mail);
        foreach($mail_error as $error){
            $errors[]=$error;
        }
        if(count($errors)==0){
            $user = User::get_user_by_mail($mail);
            if(!$user){
                $errors[] = "Can't find user with this email '$mail'. Please sign up";
            }else
            if($password!=""&&!self::check_password($password,$user->hashed_password)){
                     $errors[] = "Wrong password. Please try again";
                 }
        }
        if($password==""){//trim() not necessary
            $errors[]="Password cant be empty!";
        }
        return $errors;
    }


    public function validate_full_name_format(string $full_name): array{   
        $errors=[];
        if(strlen(trim($full_name))<3){
            $errors[] = "Full name must be at least 3 characters long";
        }
        return $errors;
    }

    public function validate_full_name(string $full_name): array{   // make full_name unique
        $errors1=[];
        $user_connected = $this->get_user_or_false();
        $user = User::get_user_by_name($full_name);
        if($user_connected){
            if($user && $user->id != $user_connected->id){ 
                $errors1[] = "name already used";
            }  
        }else{
            if($user){
                $errors1[] = "name already used";
            }
        }
        $errors=(array_merge($errors1,self::validate_full_name_format($full_name)));
        return $errors;
    }

    public function validate_iban(string $iban): array{
        $errors=[];
        
        if(strlen($iban)>0&&(!preg_match("/^([a-zA-Z]{2}[0-9]{2}(?:[\s-]?[0-9]{4}){3})$/",strtoupper($iban))))
        {
            $errors[] = "Invalid IBAN";
        }
        return $errors;
    }


    public function validate_amount(?string $amount): array{
        $errors=[];
        if($amount==null || $amount ==""){
            $errors[] = "Amount is mandatory";
        }else if(!is_numeric(trim($amount))){
            $errors[]="Amount must be a valid number";
            
        }else{
            $amount = floatval($amount);
            if($amount<=0){
                $errors[] = "Amount must be positive";
            }
        } 
        return $errors;
    }

    public function validate_weights(?array $weights): array{
        $errors=[];
        $total_weight = 0;
        
        foreach($weights as $weight){
            if(is_numeric(trim($weight))){
                $total_weight += floatval($weight);
            }
            if($weight==null || $weight==""){
                $errors[] = "Weight is mandatory";
            }
            if(!is_numeric(trim($weight))){
                $errors[] = "Invalid value for weight";
            }
            else{
                $weight = floatval($weight);
                if($weight<0){
                    $errors[] = "Weight must be positive";
                }
            }
        }
        if($total_weight==0){
            $errors[] = "You must specify at least one weight";
        }
        return $errors;
    }

    public function validate_date(?string $date): array{
        $errors=[];
        $system_date = date("Y-m-d");
        if($date==null or strlen($date)==0 or $date=="0000-00-00"){
            $errors[]= "Date is mandatory";
        }else{
            if(!Operation::validateDate($date)){
                $errors[]="Invalide date format";
            }
            if($system_date < $date){
                $errors[]="Date cannot be in the future";
            }
        }
        return $errors;
    }

    public function isJustValidateOn(): bool{
        if(Configuration::get("just_validate")){
            return true;
        }else{
            return false;
        }

    }

}

?>