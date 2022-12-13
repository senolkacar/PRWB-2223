<?php

require_once "framework/Model.php";
require_once "User.php";
require_once "Tricount.php";


class Subscription extends Model {

    public function __construct(public Tricount $tricount, public User  $user)  {   
    }

    public static function get_subscriptions_by_tricount(Tricount $tricount) :array{
        $query = self::execute("SELECT * FROM subscriptions WHERE tricount =:tricount",["tricount"=>$tricount->id]);
        $data = $query->fetchAll() ;
        $subscriptions = [];
        foreach($data as $row){
            $subscriptions[] = new Subscription(Tricount::get_tricount_by_id($row["tricount"]),User::get_user_by_id($row["user"]));
        }
        return  $subscriptions;

    }

    public static function nb_subscriptions_by_tricount(User $user) : array {
        $query = self::execute("SELECT DISTINCT tricounts.*, (SELECT count(*) FROM subscriptions WHERE subscriptions.tricount = tricounts.id and subscriptions.user<>:user) as subscription_count FROM tricounts LEFT JOIN subscriptions ON subscriptions.tricount = tricounts.id",["user"=>$user->id]);
        return $query->fetchAll() ;//(int)$data[0]
        
    }


}