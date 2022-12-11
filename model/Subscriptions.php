<?php

require_once "framework/Model.php";
require_once "User.php";
require_once "Tricount.php";


class Subscriptions extends Model {

    public function __construct(public Tricount $tricount, public User  $user)  {   
    }

    public static function nb_subscriptions_by_tricount_1(Tricount $tricount) : int {
        $query = self::execute("SELECT count(*) count FROM subscriptions  GROUP by subscriptions.tricount     having tricount = :tricount",["tricount" =>$tricount->id]);
        $data = $query->fetch();
       // $result =$data["count"];
        return (int)$data[0] ;//(int)$data[0]
        
    }

    public static function nb_subscriptions_by_tricount_0(Tricount $tricount) : int {
        $query = self::execute("SELECT * FROM subscriptions  where tricount = :tricount",["tricount" =>$tricount->id]);
        $data = $query->fetchAll();
        return $query->rowCount() ;
        
    }
    public static function nb_subscriptions_by_tricount(User $user) : array {
        $query = self::execute("SELECT DISTINCT tricounts.*, (SELECT count(*) FROM subscriptions WHERE subscriptions.tricount = tricounts.id and subscriptions.user<>:user) as subscription_count FROM tricounts LEFT JOIN subscriptions ON subscriptions.tricount = tricounts.id",["user"=>$user->idgit]);
        return $query->fetchAll() ;//(int)$data[0]
        
    }


}