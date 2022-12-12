<?php
require_once 'model/User.php';
require_once 'model/Tricount.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerTricount extends Controller {


    public function index() : void {
    
        $member=$this->get_user_or_redirect();
      
        $tricounts =[];
      

        if(isset($_GET["param1"]) && $_GET["parame1"] !=="") { 
            $member = User::get_user_by_mail($_GET["param1"]);
            }

        $tricounts =$member->get_tricounts_involved();
  
        (new View("list_tricount"))->show([
            "tricounts" => $tricounts
            ]);



    } 
    public function index_2() : void {
    
        $member=$this->get_user_or_redirect();
       // $nb_subscrptions = 0;
       $nb_subscriptions = [];
       $tricounts_id = [];
        $tricounts =[];
        $n = 0;


        if(isset($_GET["param1"]) && $_GET["parame1"] !=="") { 
            $member = User::get_user_by_mail($_GET["param1"]);
            }

        $tricounts =$member->get_tricounts_involved();
        foreach($tricounts as $trcount) {
            $tricounts_id[] = $trcount->id;
            $nb_subscriptions[] = $trcount->nb_subscriptions_by_tricount();
        }
       // var_dump($tricounts_id);//null
        var_dump($nb_subscriptions);
        echo "<br>";

        (new View("list_tricount"))->show([
            "tricounts" => $tricounts,
            "n" =>$n,
            "nb_subscriptions" => $nb_subscriptions
            ]);



    } 

    public function add_tricount():void {

        echo "add tricount";
    }

}