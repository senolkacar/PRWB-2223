
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

    public function persist(){ 
        if($this->id == null){
            self::execute("INSERT INTO users (mail,hashed_password,full_name,role,iban) VALUES (:mail,:hashed_password,:full_name,:role,:iban)",["mail"=>$this->mail,"hashed_password"=>$this->hashed_password,"full_name"=>$this->full_name,"role"=>$this->role,"iban"=>$this->iban]);
            $user = self::get_user_by_id(self::lastInsertId());
            $this->id = $user->id;
        }else{
            self::execute("UPDATE users SET hashed_password = :hashed_password, full_name = :full_name, role = :role, iban = :iban WHERE mail = :mail",["hashed_password"=>$this->hashed_password,"full_name"=>$this->full_name,"role"=>$this->role,"iban"=>$this->iban,"mail"=>$this->mail]);
        }
    }



    public function get_tricounts_involved(): array{
        return Tricount::get_tricounts_involved($this);

    }
    public function add_tricount(Tricount $tricount) : Tricount {
        return $tricount -> persist();
    }

    public static function get_users_not_subscriber_by_tricount(Tricount $tricount): array {
        $query = self::execute("SELECT * FROM users WHERE id <> :user and id not in 
        (select user from subscriptions where tricount = :tricount ) ORDER BY full_name ASC",  ["user" => $tricount->creator->id, "tricount" =>$tricount->id]);
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

    public function has_operation(Tricount $tricount) : bool {
        $query = self::execute("SELECT count(*) FROM repartitions where user=:user and operation in (select id from operations where tricount=:tricount)", ["user"=>$this->id, "tricount"=>$tricount->id]);
        $data = $query->fetch();
        return ((int)$data[0]) > 0;
    }

    public function is_initiator(Tricount $tricount) : bool {
       $query = self::execute("SELECT count(*) FROM operations where initiator=:user and tricount=:tricount", ["user"=>$this->id,"tricount"=>$tricount->id]);
        $data = $query->fetch();
        return ((int)$data[0]) > 0;
     }
    public function is_initiator_check(int $operationid) : bool {
        $query = self::execute("SELECT count(*) FROM operations where id=:id and initiator=:user ", ["user"=>$this->id,"id"=>$operationid]);
        $data = $query->fetch();
        return ((int)$data[0]) > 0;
    } 

    public function is_involved(int $tricountid) : bool{ // without creator
        $query = self::execute("SELECT count(*) FROM subscriptions where user=:user and tricount=:tricount",["user"=>$this->id,"tricount"=>$tricountid]);
        $data = $query->fetch();
        return ((int)$data[0]) > 0;
    }

    public function is_creator(int $tricountid) : bool {
        $query = self::execute("SELECT count(*) FROM tricounts where id=:tricount and creator=:user ", ["tricount"=>$tricountid,"user"=>$this->id]);
        $data = $query->fetch();
        return ((int)$data[0]) > 0;
    }

    public function is_involved_in_operation(int $operationid) : bool{
        $query = self::execute("SELECT count(*) FROM repartitions where operation=:operation and user=:user",["user"=>$this->id,"operation"=>$operationid]);
        $data = $query->fetch();
        return ((int)$data[0]) > 0;

    } // not include member with weight 0
 


}
?>