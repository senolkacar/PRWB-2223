<?php

require_once "framework/Model.php";
require_once "Subscription.php";
require_once "User.php";
require_once "Tricount.php";

Class Operation extends Model{


    public function __construct(public string $title, 
        public Tricount $tricount,
        public float $amount,
        public User $initiator,
        public ?String $operation_date= NULL,         
         public ?String $created_at = NULL,
         public ?int $id = NULL){}


    public function get_total_weight():int{
        return Repartition::get_total_weight_by_operation($this->id);
    }
    public static function get_operations(): array{
    $query = self::execute("SELECT * FROM operations",[]);
    $data = $query->fetchAll();
    $operations = [];
    foreach($data as $row){

        $operations[] = new Operation($row["title"],Tricount::get_tricount_by_id($row["tricount"]),
        $row["amount"],User::get_user_by_id($row["initiator"]) ,$row["operation_date"],$row["created_at"]);
    return $operations;
    }
}

    public static function get_operation_by_id(int $id): Operation|false{
        $query = self::execute("SELECT * FROM operations WHERE id = :id",[":id" => $id]);
        $data = $query->fetch();
        if($query->rowCount()==0){
            return false;
        }else{
            return new Operation($data["title"],Tricount::get_tricount_by_id($data["tricount"]),$data["amount"],User::get_user_by_id($data["initiator"]),
            $data["operation_date"],$data["created_at"],$data["id"]);
        }
    }

    public static function validate_title(string $title): array{
        $errors=[];
        if(!strlen($title)>3){
            $errors[] = "Title must be at least 3 characters long";
        }
        return $errors;
    }


    public static function validate_amount(float $amount): array{
        $errors=[];
        if($amount<=0){
            $errors[] = "Amount must be greater than 0";
        }
        return $errors;
    }


   


}
?>