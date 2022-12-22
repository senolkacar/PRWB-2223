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
        $query = self::execute("SELECT * FROM operations WHERE id = :id",["id" => $id]);
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
        if($title==null or strlen($title)==0){
            $errors[]= "Title is mandatory";
        } elseif(strlen($title)<3){
            $errors[] = "Title must have at least 3 characters";
        }
        return $errors;
    }


    public static function validate_amount(float $amount): array{
        $errors=[];
        if($amount==null){
            $errors[] = "Amount is mandatory";
        }
        if(!is_numeric($amount)){
            $errors[] = "Invalid value for amount";
        }
        else{
            $amount = floatval($amount);
            if($amount<=0){
                $errors[] = "Amount must be positive";
            }
        }
       
        return $errors;
    }

    public function validate(): array{
        $errors=[];
        if($this->title==null or strlen($this->title)==0){
            $errors[]= "Title is mandatory";
        } elseif(strlen($this->title)<3){
            $errors[] = "Title must have at least 3 characters";
        }
        if($this->amount<=0){
            $errors[] = "Amount must be positive";
        }
        return $errors;
    }

    public static function get_operations_by_tricount(Tricount $tricount): array{
        $query = self::execute("SELECT * FROM operations WHERE tricount = :tricount order by created_at",["tricount" => $tricount->id]);
        $data = $query->fetchAll();
        $operations = [];
        foreach($data as $row){
            $operations[] = new Operation($row["title"],$tricount,$row["amount"],User::get_user_by_id($row["initiator"]),
            $row["operation_date"],$row["created_at"],$row["id"]);
        }
        return $operations;
    }
    
    public static function get_my_total(Tricount $tricount,User $user):float{
        $depenses = $tricount->get_depenses();
            $mytotal = 0;
            foreach($depenses as $operation){
                $total_weight = Repartition::get_total_weight_by_operation($operation["id"]);
                $weight = Repartition::get_user_weight($user->id, $operation["id"]);
                $mytotal+= $operation["amount"] * $weight / $total_weight;
            }
           return $mytotal = number_format($mytotal, 2, '.', '');
        
    }
    
    public static function get_total(Tricount $tricount):float{
        $depenses = $tricount->get_depenses();
            $total = 0;
            foreach($depenses as $operation){
                $total+= $operation["amount"];
            }
           return $total = number_format($total, 2, '.', '');
        
    }


    public function persist():Operation {
        if($this->id!==null){
            self::get_operation_by_id($this->id);
                self::execute('UPDATE operations SET title=:title, tricount=:tricount, amount=:amount, initiator=:initiator, operation_date=:operation_date WHERE id=:id', 
                ['title' => $this->title,
                 'tricount' => $this->tricount->id,
                 'amount' => $this->amount,
                 'initiator' =>$this->initiator->id,
                 'operation_date' =>$this->operation_date,
                 'id' => $this->id
                ]);
        }else{
                self::execute('INSERT INTO operations (title, tricount,amount,initiator, operation_date) VALUES (:title, :tricount,:amount,:initiator, :operation_date)', 
                               ['title' => $this->title,
                                'tricount' => $this->tricount->id,
                                'amount' => $this->amount,
                                'initiator' =>$this->initiator->id,
                                'operation_date' =>$this->operation_date
                               ]);
                $operation = self::get_operation_by_id(self::lastInsertId());
                $this->id = $operation->id;
                $this->created_at = $operation->created_at;
            }
            return $this;
    }

    public static function delete(Tricount $tricount) : bool {
        //if ($user == $tricount->creator) 
            self::execute('DELETE FROM operations WHERE tricount=:tricount', ['tricount' => $tricount->id]);    
        return true;
    }

    public function get_users_by_operation_id(){
        $query = self::execute("select * from users where id in(select initiator from operations where id=:operation) union select * from users where id in(select user from repartitions where operation=:operation)",["operation" => $this->id]);
        $data = $query->fetchAll();
        $users = [];
        foreach($data as $row){
            $users[] = new User($row["mail"],$row["hashed_password"],$row["full_name"],$row["role"],$row["iban"],$row["id"]);
        }
        return $users;
    }


}
?>