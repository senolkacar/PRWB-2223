<?php
require_once "framework/Model.php";
require_once "Subscription.php";
require_once "User.php";
require_once "Operation.php";

Class Tricount extends Model{
    public function __construct(public string $title,
        public User $creator,
        public ?string $description = '',
        public ?String $created_at = NULL,
        public ?int $id = NULL)
    {
        
    }

    public static function get_tricount_by_id(int $id): Tricount|false{
        $query = self::execute("SELECT * FROM tricounts WHERE id = :id",[":id" => $id]);
        $data = $query->fetch();
        if($query->rowCount()==0){
            return false;
        }else{
            return new Tricount($data["title"],User::get_user_by_id($data["creator"]),$data["description"],$data["created_at"],$data["id"]);
        }
    }

    public static function validate_title(string $title): array{
        $errors=[];
        if(strlen(trim($title))<3){
            $errors[] = "Title must be at least 3 characters long";
        }
        return $errors;
    }

    public static function validate_description(string $description): array{
        $errors=[];
        if(strlen(trim($description))>0 && strlen(trim($description))<3){
            $errors[] = "Description must be at least 3 characters long";
        }
        return $errors;
    }

    public function validate(): array{        
        
        $errors=(array_merge($this::validate_title($this->title),$this::validate_description($this->description))); 

        return $errors;
    }

    public function persist():Tricount {
        if($this->id == NULL) {
           $errors = $this->validate();
            if(empty($errors)){
                self::execute('INSERT INTO Tricounts (title, description, creator) VALUES (:title,:description,:creator)', 
                               ['title' => $this->title,
                                'description' => $this->description,
                                'creator' => $this->creator->id// user ? int?
                               ]);
                $tricount = self::get_tricount_by_id(self::lastInsertId());
                $this->id = $tricount->id;
               $this->created_at = $tricount->created_at;
                return $this;
            } else {
               return $errors; 
            }
        } else {
            //on ne modifie jamais les messages : pas de "UPDATE" SQL.
            throw new Exception("Not Implemented.");
        }
    }

    public function update():Tricount{
        $errors = $this ->validate();
        if(empty($errors)){
           self:: execute("UPDATE tricounts SET title=:title, description=:description WHERE id=$this->id ", 
            ["title"=>$this->title, "description"=>$this->description]);
        }

        return $this;        
    }

    public static function get_tricounts_involved(User $user): array{    
        $query = self::execute("SELECT DISTINCT tricounts.*, (SELECT count(*) FROM subscriptions WHERE subscriptions.tricount = tricounts.id)
         as subscription_count  FROM tricounts LEFT JOIN subscriptions ON subscriptions.tricount = tricounts.id 
         where tricounts.creator = :user or subscriptions.user = :user ",["user"=>$user->id]);
        
        $data = $query->fetchAll();
         $tricounts = [];
         foreach($data as $row){
             $tricounts[] = [new Tricount($row["title"],User::get_user_by_id($row["creator"]) ,$row["description"],$row["created_at"], $row["id"]),
              $row["subscription_count"]];
         }
                 
        return $tricounts;

    }

    public function get_depenses() : array {//should return object
        $query = self::execute("SELECT * FROM operations where tricount =:tricount order by operation_date desc",["tricount" =>$this->id]);
        $data = $query->fetchAll();
        $depenses=[];
        foreach($data as $row){
            $depenses[] = new Operation($row["title"],Tricount::get_tricount_by_id($row["tricount"]),$row["amount"],User::get_user_by_id($row["initiator"]),$row["operation_date"],$row["created_at"],$row["id"]);
        }
        return $depenses;
    }

    public function get_nb_participants(): int{
        $query = self::execute("SELECT count(*) FROM subscriptions WHERE tricount = :tricount",["tricount" => $this->id]);
        $data = $query->fetch();
        return $data[0];
    }

    public function get_nb_participants_including_creator(): int{
        return $this->get_nb_participants() +1;
    }

    public function get_users_including_creator(): array{
        $query = self::execute("SELECT * FROM users WHERE id = :creator UNION SELECT users.* FROM users INNER JOIN subscriptions ON subscriptions.user = users.id WHERE subscriptions.tricount = :tricount",["creator" => $this->creator->id, "tricount" => $this->id]);
        $data = $query->fetchAll();
        $users = [];
        foreach($data as $row){
            $users[] = new User($row["mail"],$row["hashed_password"],$row["full_name"],$row["role"],$row["iban"],$row["id"]);
        }
        return $users;
    }


    public function get_subscriptions(): array {
        return Subscription::get_subscriptions_by_tricount($this);
    }

    public function get_users_not_subscriber(): array {
        return User::get_users_not_subscriber_by_tricount($this);
    }

    public function get_operations_by_tricount(): array {
        return Operation::get_operations_by_tricount($this);
    }

    public function get_balance_by_tricount(): array {
        return Repartition::get_balance_by_tricount($this);
    }
    
    public function delete_repartition_templates(): void {
        self::execute('DELETE FROM repartition_template_items WHERE repartition_template IN  (SELECT id FROM repartition_templates WHERE tricount = :tricount)'
        , ['tricount' => $this->id]);

        self::execute('DELETE FROM repartition_templates WHERE tricount = :tricount' , ['tricount' => $this->id]);       

    }

    public function delete(User $user):Tricount|false{
        if (Repartition::delete($this))
            if(Operation::delete($this))
               if(Subscription::delete($this)){
                    $this ->delete_repartition_templates();
                    self::execute('DELETE FROM tricounts WHERE id=:id', ['id' => $this->id]);                        
                    }
        return $this;               
      
    }
    

    

}





?>