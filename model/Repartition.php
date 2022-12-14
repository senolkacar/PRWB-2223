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
        return $data[0];
    }

    public static function get_user_weight(int $user, int $operation): int{
        $query = self::execute("SELECT weight FROM repartitions WHERE user = :user AND operation = :operation",["user" => $user, "operation" => $operation]);
        $data = $query->fetch();
        return $data[0];
    }


    //add

    //delete

    //get weight by opearion and user



}
