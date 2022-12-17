<?php

require_once "framework/Model.php";


class Repartition extends Model {

    public function __construct(
        public Operation $operation, 
        public User $user, 
        public int $weight)  {}

    public static function get_total_weight_by_operation(int $id): int {
        $query = self::execute("SELECT SUM(weight) FROM repartitions WHERE operation = :operation", ["operation" => $id]);
        $data = $query->fetch();
        return (int)$data[0];
    }

    public static function get_user_weight(int $user, int $operation): int{
        $query = self::execute("SELECT weight FROM repartitions WHERE user = :user AND operation = :operation",["user" => $user, "operation" => $operation]);
        $data = $query->fetch();
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

    public static function get_amount_by_user_and_operation(User $user, Operation $operation): float {
        $weight_total = Repartition::get_total_weight_by_operation($operation->id);
        $weight_user = Repartition::get_user_weight($user->id,$operation->id);
        $operation_amount= $operation->amount;
        return round($operation_amount/$weight_total*$weight_user,2);
    
    }

    //fonction return for a tricount each user full_name and balance, balance is initialized at 0 calculated by subtracting every operation amount to the user balance and add the amount of the operation to the user balance if he is the initiator of the operation
    public static function get_balance_by_tricount(Tricount $tricount): array {
        $users = $tricount->get_users_including_creator();
        $operations = Operation::get_operations_by_tricount($tricount);
        $balance = [];
        foreach($users as $user){
            $balance[$user->full_name] = 0;
        }
        foreach($operations as $operation){
            foreach($users as $user){
                $user_weight = Repartition::get_user_weight($user->id,$operation->id);
                $total_weight = Repartition::get_total_weight_by_operation($operation->id);
                if($operation->initiator->id == $user->id){
                    $balance[$user->full_name] += $operation->amount;
                }else{
                    $balance[$user->full_name] -= $operation->amount * $user_weight  / $total_weight;
                }
            }
        }
        return $balance;
    }
   
    //add

    //delete

    //get weight by opearion and user



}
