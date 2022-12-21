<?php

require_once "framework/Model.php";


class Repartition extends Model {

    public function __construct(
        public Operation $operation, 
        public User $user, 
        public int $weight)  {}


    public function persist() : Repartition{
        if(self::get_repartition_by_id($this->id)){
            self::execute("UPDATE Repartitions SET operation=:operation,user=:user,weight=:weight WHERE id=:id",
            ["operation"=>$this->operation->id,"user"=>$this->user->id,"weight"=>$this->weight,"id"=>$this->id]);
        }else{
            self::execute("INSERT INTO Repartitions (operation,user,weight) VALUES (:operation,:user,:weight)",
         ["operation"=>$this->operation->id,"user"=>$this->user->id,"weight"=>$this->weight]);
        }
        return $this;
     }

    public static function get_total_weight_by_operation(int $id): int {
        $query = self::execute("SELECT SUM(weight) FROM repartitions WHERE operation = :operation", ["operation" => $id]);
        $data = $query->fetch();
        return (int)$data[0];
    }

    public static function get_user_weight(int $user, int $operation): int{
        $query = self::execute("SELECT weight FROM repartitions WHERE user = :user AND operation = :operation",["user" => $user, "operation" => $operation]);
        $data = $query->fetch();
        if($data==false){
            return 0;
        }
        return (int)$data[0];
    }

    public static function include_user(User $user, Operation $operation): bool {
        $query = self::execute("SELECT count(*) FROM repartitions WHERE operation = :operation AND user=:user" , [":operation" => $operation->id,":user" => $user->id]);
        $data = $query->fetch();
        return ((int)$data[0])>0 ;
    }

    public static function get_repartitions_by_operation(Operation $operation):array|false {
        $query = self::execute("SELECT * FROM repartitions WHERE operation = :operation " , [":operation" => $operation->id]);
        $repartitions =[];
        if ($query->rowCount() == 0) {
            return false;
        } else {
            $data = $query->fetchAll();
            foreach($data as $row){
                $repartitions[] = new Repartition(Operation::get_operation_by_id($row["operation"]),User::get_user_by_id($row["user"]),$row["weight"]);
            }
           return $repartitions;
        }

    }

    public static function get_repartition_by_id(int $id): Repartition|false {
        $query = self::execute("SELECT * FROM repartitions WHERE id = :id", [":id" => $id]);
        $data = $query->fetch();
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new Repartition(Operation::get_operation_by_id($data["operation"]),User::get_user_by_id($data["user"]),$data["weight"]);
        }
    }

    public static function get_amount_by_user_and_operation(User $user, Operation $operation): float {
        $weight_total = Repartition::get_total_weight_by_operation($operation->id);
        $weight_user = Repartition::get_user_weight($user->id,$operation->id);
        $operation_amount= $operation->amount;
        return round($operation_amount/$weight_total*$weight_user,2);
    
    }

    //fonction return for a tricount each user full_name and balance, balance is initialized at 0 
    //calculated by subtracting every operation amount to the user balance and add the amount of the operation to the user balance if he is the initiator of the operation
    public static function get_balance_by_tricount(Tricount $tricount): array {
        $users = $tricount->get_users_including_creator();
        $operations = Operation::get_operations_by_tricount($tricount);
        $balance = [];
        foreach($users as $user){
            $balance[$user->full_name] = 0.00;
            $balance[$user->full_name] -= round(Operation::get_my_total($tricount,$user),2);
        }
        foreach($operations as $operation){
            $balance[$operation->initiator->full_name] += round($operation->amount,2);
        }
        return $balance;
    }
   
    public static function delete(Tricount $tricount) : bool {
        //if ($user == $tricount->creator) 
            self::execute('DELETE FROM repartitions WHERE operation in 
            (select id from operations where tricount=:tricount)', ['tricount' => $tricount->id]);
    
        return true;
    }



}
