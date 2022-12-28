
<?php
require_once 'framework/Model.php';


Class User extends Model{
    public function __construct( public string $mail,public string $hashed_password, public string $full_name, public string $role, public ?string $iban = null,public ?int $id = NULL){}
    
    public static function get_user_by_mail(string $mail): User|false{
        $query = self::execute("SELECT * FROM users WHERE mail = :mail",[":mail" => $mail]);
        $data = $query->fetch();
        if($query->rowCount()==0){
            return false;
        }else{
            return new User($data["mail"],$data["hashed_password"],$data["full_name"],$data["role"],$data["iban"],$data["id"]);
        }
    }

    public static function get_user_by_id(int $id): User|false{
        $query = self::execute("SELECT * FROM users WHERE id = :id",[":id" => $id]);
        $data = $query->fetch();
        if($query->rowCount()==0){
            return false;
        }else{
            return new User($data["mail"],$data["hashed_password"],$data["full_name"],$data["role"],$data["iban"],$data["id"]);
        }
    }

    public static function get_user_by_name(string $full_name): User|false{
        $query = self::execute("SELECT * FROM users WHERE full_name = :full_name",[":full_name" => $full_name]);
        $data = $query->fetch();
        if($query->rowCount()==0){
            return false;
        }else{
            return new User($data["mail"],$data["hashed_password"],$data["full_name"],$data["role"],$data["iban"],$data["id"]);
        }
    }


    public static function get_users(): array{
        $query = self::execute("SELECT * FROM users",[]);
        $data = $query->fetchAll();
        $users = [];
        foreach($data as $row){
            $users[] = new User($row["mail"],$row["hashed_password"],$row["full_name"],$row["role"],$row["iban"],$row["id"]);
        }
        return $users;
    }

    public function get_other_users() : array{
        $query = self::execute("SELECT * FROM users WHERE mail <> :mail",["mail"=>$this->mail]);
        $data = $query->fetchAll();
        $users = [];
        foreach($data as $row){
            $users[] = new User($row["mail"],$row["hashed_password"],$row["full_name"],$row["role"],$row["iban"],$row["id"]);
        }
        return $users;
    
    }

    public function persist() : User{
        if(self::get_user_by_mail($this->mail)){
            self::execute("UPDATE users SET hashed_password = :hashed_password, full_name = :full_name, role = :role, iban = :iban WHERE mail = :mail",["hashed_password"=>$this->hashed_password,"full_name"=>$this->full_name,"role"=>$this->role,"iban"=>$this->iban,"mail"=>$this->mail]);
        }else{
            self::execute("INSERT INTO users (mail,hashed_password,full_name,role,iban) VALUES (:mail,:hashed_password,:full_name,:role,:iban)",["mail"=>$this->mail,"hashed_password"=>$this->hashed_password,"full_name"=>$this->full_name,"role"=>$this->role,"iban"=>$this->iban]);
        }
        return $this;
    }

    public function persist_by_id() : User{
        if(self::get_user_by_id($this->id)){
            self::execute("UPDATE users SET hashed_password = :hashed_password, full_name = :full_name, role = :role, iban = :iban WHERE mail = :mail",["hashed_password"=>$this->hashed_password,"full_name"=>$this->full_name,"role"=>$this->role,"iban"=>$this->iban,"mail"=>$this->mail]);
        }else{
            self::execute("INSERT INTO users (mail,hashed_password,full_name,role,iban) VALUES (:mail,:hashed_password,:full_name,:role,:iban)",["mail"=>$this->mail,"hashed_password"=>$this->hashed_password,"full_name"=>$this->full_name,"role"=>$this->role,"iban"=>$this->iban]);
        }
        return $this;
    }

    public static function validate_email(string $mail): array{
        $errors=[];
        $user = self::get_user_by_mail($mail);
        if($user){
            $errors[] = "Email already used";
        }
        if(!strlen($mail)>0){
            $errors[] = "Email is required";
        }
        elseif(!filter_var($mail,FILTER_VALIDATE_EMAIL)){
            $errors[] = "Invalid email";
        }
        return $errors;
    }

    public function validate_email_for_edit(string $mail): array{
        $errors=[];
        $user = self::get_user_by_mail($mail);
        if($user && $user->id != $this->id){
            $errors[] = "Email already used";
        }
        if(!strlen($mail)>0){
            $errors[] = "Email is required";
        }
        elseif(!filter_var($mail,FILTER_VALIDATE_EMAIL)){
            $errors[] = "Invalid email";
        }
        return $errors;
    }

    public static function validate_password(string $password): array{
        $errors=[];
        if(strlen($password)<8||strlen($password)>16){
            $errors[] = "Password length must be between 8 and 16 characters";
        }
        if(!((preg_match("/[A-Z]/",$password))&&preg_match("/\d/",$password)&&preg_match("/['\";:,.\/?!\\-]/",$password))){
            $errors[] = "Password must contain at least one uppercase letter, one number and one special character";
        }
        return $errors;
    }

    public static function check_password(string $clear_password, string $hash): bool{
        return $hash === Tools::my_hash($clear_password);
    }

    public static function validate_passwords(string $password, string $password_confirm): array{
        //should we show the validation for each password ?
        //$errors=User::validate_password($password_confirm);
        $errors=[];
        if($password !== $password_confirm){
            $errors[] = "Passwords don't match";
        }
        return $errors;
    }

    public static function validate_login(string $mail, string $password): array{
        $errors = [];
        $user = User::get_user_by_mail($mail);
        if($user){
            if(!self::check_password($password,$user->hashed_password)){
                $errors[] = "Wrong password. Please try again";
            }
        }else{
            $errors[] = "Can't find user with this email '$mail'. Please sign up";
        }
        return $errors;
    }

    public static function validate_full_name(string $full_name): array{        
        $errors=[];
        if(strlen($full_name)<3){
            $errors[] = "Full name must be at least 3 characters long";
        }
        return $errors;
    }

    public static function validate_iban(string $iban): array{
        $errors=[];
        
        if(strlen($iban)>0&&(!preg_match("/^[A-Z]{2}\d{2} [\d ]{4} [\d ]{4} [\d ]{4}$/",strtoupper($iban)))){
            $errors[] = "Invalid IBAN";
        }
        return $errors;
    }

    public function get_tricounts_involved(): array{
        return Tricount::get_tricounts_involved($this);

    }
    public function add_tricount(Tricount $tricount) : Tricount {
        return $tricount -> persist();
    }

    public static function get_users_not_subscriber_by_tricount(Tricount $tricount): array {
        $query = self::execute("SELECT * FROM users WHERE id <> :user and id not in 
        (select user from subscriptions where tricount = :tricount)",  ["user" => $tricount->creator->id, "tricount" =>$tricount->id]);
        $data = $query->fetchAll();
        $users = [];
        foreach($data as $row) {
            $users[] = new User($row["mail"],$row["hashed_password"],$row["full_name"],$row["role"],$row["iban"],$row["id"]);
        }
        return $users;

    }

    public static function get_users_by_tricount(Tricount $tricount): array {
        $query = self::execute("SELECT * FROM users where id in(SELECT user FROM subscriptions WHERE tricount = :tricount) OR id in (SELECT creator from tricounts WHERE id = :tricount)",
          [ "tricount" =>$tricount->id]);
        $data = $query->fetchAll();
        $users = [];
        foreach($data as $row) {
            $users[] = new User($row["mail"],$row["hashed_password"],$row["full_name"],$row["role"],$row["iban"],$row["id"]);
        }
        return $users;

    }




   


}
?>