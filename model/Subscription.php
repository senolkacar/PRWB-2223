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

    private static function get_subscription_by_tricount_and_user(Tricount $tricount, User $user) :Subscription{
        $query = self::execute("SELECT * FROM subscriptions WHERE tricount =:tricount and user=:user",["tricount"=>$tricount->id, "user"=>$user->id]);
        $data = $query->fetch() ;
        $subscription = new Subscription(Tricount::get_tricount_by_id($data["tricount"]),User::get_user_by_id($data["user"]));

        return  $subscription;

    }

    public static function persist(User $user, Tricount $tricount) : Subscription|array {
        self::execute('INSERT INTO Subscriptions (tricount, user) VALUES (:tricount,:user)', 
                               ['tricount' => $tricount->id,
                                'user' => $user->id
                               ]);
        $subscription= self::get_subscription_by_tricount_and_user($tricount, $user);
        return $subscription;
    }

    public static function delete(Tricount $tricount) : bool {
        //check if a members of the tricount?
        self::execute('DELETE FROM subscriptions WHERE tricount=:tricount', ['tricount' => $tricount->id]);    
        return true;
    }

    public static function delete_subscription(Tricount $tricount, User $user) : bool {
        //check if initiator or repartition of one operation 
        self::execute('DELETE FROM subscriptions WHERE tricount=:tricount and user=:user', ['tricount' => $tricount->id, 'user' => $user ->id]);    
        return true;
    }


}