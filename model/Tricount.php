<?php
require_once "framework/Model.php";
require_once "Subscriptions.php";

Class Tricount extends Model{
    public function __construct(public string $title,
        public User $creator,
        public ?string $description = '',
        public ?String $created_at = NULL,
        public ?int $id = NULL)
    {
        
    }

    public static function get_tricounts(): array{
        $query = self::execute("SELECT * FROM tricounts",[]);
        $data = $query->fetchAll();
        $tricounts = [];
        foreach($data as $row){
            $tricounts[] = new Tricount($row["title"],$row["description"],$row["created_at"],$row["creator"]);
        }
        return $tricounts;
    }

    public static function get_tricount_by_id(int $id): Tricount|false{
        $query = self::execute("SELECT * FROM tricounts WHERE id = :id",[":id" => $id]);
        $data = $query->fetch();
        if($query->rowCount()==0){
            return false;
        }else{
            return new Tricount($data["title"],User::get_user_by_id($data["creator"]),$data["description"],$data["created_at"],);
        }
    }

    public static function get_tricount_by_creator(int $creator): Tricount|false{
        $query = self::execute("SELECT * FROM tricounts WHERE creator = :creator",[":creator" => $creator]);
        $data = $query->fetch();
        if($query->rowCount()==0){
            return false;
        }else{
            return new Tricount($data["title"],$data["description"],$data["created_at"],$data["creator"]);
        }
    }

    public function validate_title(string $title): array{
        $errors=[];
        if(!strlen($title)>3){
            $errors[] = "Title must be at least 3 characters long";
        }
        return $errors;
    }

    public function validate_description(string $description): array{
        $errors=[];
        if(strlen($description)>0&&strlen($description)<3){
            $errors[] = "Description must be at least 3 characters long";
        }
        return $errors;
    }

    public function persist():Tricount {
        if($this->id == NULL) {
           // $errors = $this->validate();
            //if(empty($errors)){
                self::execute('INSERT INTO Tricounts (title, description, creator) VALUES (:title,:description,:creator)', 
                               ['title' => $this->title,
                                'description' => $this->description,
                                'creator' => $this->creator->id// user ? int?
                               ]);
                $tricount = self::get_tricount_by_id(self::lastInsertId());
                $this->id = $tricount->id;
               $this->created_at = $tricount->created_at;
                return $this;
            //} else {
           //     return $errors; 
           // }
        } else {
            //on ne modifie jamais les messages : pas de "UPDATE" SQL.
            throw new Exception("Not Implemented.");
        }
    }

    public static function get_tricounts_involved(User $user): array{    
        $query = self::execute("SELECT DISTINCT tricounts.*, (SELECT count(*) FROM subscriptions WHERE subscriptions.tricount = tricounts.id)
         as subscription_count  FROM tricounts LEFT JOIN subscriptions ON subscriptions.tricount = tricounts.id 
         where tricounts.creator = :user or subscriptions.user = :user ",["user"=>$user->id]);
         
        return $query->fetchAll();

    }

    public function get_depenses() : array {
        $query = self::execute("SELECT * FROM operations WHERE tricount = :tricount",["tricount" =>$this->id]);
        return $query->fetchAll();
    }

    public static function get_tricount_by_name(string $name): Tricount|false{
        $query = self::execute("SELECT * FROM tricounts WHERE title = :title",["title" => $name]);
        $data = $query->fetch();
        if($query->rowCount()==0){
            return false;
        }else{
            return new Tricount($data["title"],User::get_user_by_id($data["creator"]),$data["description"],$data["created_at"],$data["id"]);
        }
    }

    public function get_nb_participants(): int{
        $query = self::execute("SELECT count(*) FROM subscriptions WHERE tricount = :tricount",["tricount" => $this->id]);
        $data = $query->fetch();
        return $data[0];
    }


    

}





?>