<?php
require_once "framework/Model.php";
require_once "Subscriptions.php";

Class Tricount extends Model{
    public function __construct(public string $title,public ?string $description,public String $created_at,public int $creator,public ?int $id = NULL)
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
            return new Tricount($data["title"],$data["description"],$data["created_at"],$data["creator"]);
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

    public static function nb_subscriptions_by_tricount(User $user) : array {
        
        return Subscriptions::nb_subscriptions_by_tricount($user);
        
    }

    public static function get_tricounts_involved(User $user): array{
        $query = self::execute("SELECT * FROM tricountS WHERE creator =:user 
        or id IN (SELECT tricount FROM subscriptions WHERE user = :user)",[":user" =>$user->id]);
        $data = $query->fetchAll();
        $tricounts = [];
        foreach($data as $row){
            $tricounts[] = new Tricount($row["title"],$row["description"],$row["created_at"],$row["creator"]);
        }
        return $tricounts;

    }

}





?>