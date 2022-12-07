<?php
Class Operation extends Model{

    public function __construct(public string $title, public int $tricount,public float $amount,public DateTime $operation_date, public int $initiator,public DateTime $created_at){}

    public static function get_operations(): array{
    $query = self::execute("SELECT * FROM operations",[]);
    $data = $query->fetchAll();
    $operations = [];
    foreach($data as $row){
        $operations[] = new Operation($row["title"],$row["tricount"],$row["amount"],$row["operation_date"],$row["initiator"],$row["created_at"]);
    }
    return $operations;
    }

    public static function get_operation_by_id(int $id): Operation|false{
        $query = self::execute("SELECT * FROM operations WHERE id = :id",[":id" => $id]);
        $data = $query->fetch();
        if($query->rowCount()==0){
            return false;
        }else{
            return new Operation($data["title"],$data["tricount"],$data["amount"],$data["operation_date"],$data["initiator"],$data["created_at"]);
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