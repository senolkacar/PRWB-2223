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
        $query = self::execute("SELECT * FROM tricounts WHERE id = :id",["id" => $id]);
        $data = $query->fetch();
        if($query->rowCount()==0){
            return false;
        }else{
            return new Tricount($data["title"],User::get_user_by_id($data["creator"]),$data["description"],$data["created_at"],$data["id"]);
        }
    }

    public static function get_tricount_by_title_creator(User $user,String $title): Tricount|false{
        $query = self::execute("SELECT * FROM tricounts WHERE title=:title and creator=:creator",["title" => $title, "creator"=>$user->id]);
        $data = $query->fetch();
        if($query->rowCount()==0){
            return false;
        }else{
            return new Tricount($data["title"],User::get_user_by_id($data["creator"]),$data["description"],$data["created_at"],$data["id"]);
        }
    }


    public static function validate_description(string $description): array{
        $errors=[];
        if(strlen(trim($description))>0 && strlen(trim($description))<3){
            $errors[] = "Description must be at least 3 characters long";
        }
        return $errors;
    }
    private static function validate_title_format(?string $title): array{
        $errors=[];
        if($title==null || strlen($title)==0 || $title==""){
            $errors[]= "Title is mandatory";
        } elseif(strlen(trim($title))<3 || empty(trim($title))){
            $errors[]= "Title must have at least 3 characters(excluding white spaces)";
        }
        return $errors;
    }

    public static function validate_title(User $user,Tricount $tricount): array{
        $errors = [];
        $errors=self::validate_title_format($tricount->title);
        if(count($errors)==0){
            if(self::title_creator_existe($user, $tricount->title) ){
                if($tricount->id == NULL){
                    $errors[]="title and creator should be unique";
                }else{
                    $tricount1=self::get_tricount_by_title_creator($user,$tricount->title);//new
                    if($tricount1->id != $tricount->id) {
                        $errors[]="title and creator should be unique";
                    }
                }           
            }

        }
        return $errors;
    }

    public static function title_creator_existe(User $user, String $title) : bool {
        $query = self::execute("SELECT COUNT(*) FROM tricounts WHERE title=:title and creator=:creator", ["title"=>$title,"creator"=>$user->id]);
        $data = $query->fetch();
        return ((int)$data[0]) > 0;
    }

    public function persist():Tricount {//validation done in the ControllerTricount
        if($this->id == NULL) {           
                self::execute('INSERT INTO Tricounts (title, description, creator) VALUES (:title,:description,:creator)', 
                               ['title' => $this->title,
                                'description' => $this->description,
                                'creator' => $this->creator->id
                               ]);
                $tricount = self::get_tricount_by_id(self::lastInsertId());
                $this->id = $tricount->id;
               $this->created_at = $tricount->created_at;
                return $this;         
        } else {
            //on ne modifie jamais les messages : pas de "UPDATE" SQL.
            throw new Exception("Not Implemented.");
        }
    }

    public function update():Tricount{  //validation done in the ControllerTricount        
        self:: execute("UPDATE tricounts SET title=:title, description=:description WHERE id=$this->id ", 
            ["title"=>$this->title, "description"=>$this->description]);     

        return $this;        
    }

    public static function get_tricounts_involved(User $user): array{    
        $query = self::execute("SELECT DISTINCT tricounts.*, (SELECT count(*) FROM subscriptions WHERE subscriptions.tricount = tricounts.id)
         as subscription_count  FROM tricounts LEFT JOIN subscriptions ON subscriptions.tricount = tricounts.id 
         where tricounts.creator = :user or subscriptions.user = :user order by created_at desc",["user"=>$user->id]);
        
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


    public function get_users_including_creator(): array{
        $query = self::execute("SELECT * FROM users WHERE id = :creator UNION SELECT users.* FROM users INNER JOIN subscriptions 
        ON subscriptions.user = users.id WHERE subscriptions.tricount = :tricount ORDER BY full_name ASC",["creator" => $this->creator->id, "tricount" => $this->id]);
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

    public function get_balance_by_tricount(): array {//could be deleted ?
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
    
    public function get_total(){
        return Operation::get_total($this);
    }

    public function get_my_total(User $user){
        return Operation::get_my_total($this,$user);
    }

    public function get_max_balance(){
       $balance = $this->get_balance_by_tricount();
        $max = 0;
        foreach($balance as $amount){
                if(abs($amount)>$max){
                    $max = abs($amount);
                }
            }
        return $max = round($max,2);
    }

    public static function get_current_page(Operation $operation):int{
        $operations = $operation->tricount->get_depenses();
        $current_page = 0;
        $pages = count($operations);
        for ($i = 0; $i < $pages; ++$i){
            if ($operations[$i]->id == $operation->id)
                $current_page = $i;
            }
        return $current_page;
    }
}





?>