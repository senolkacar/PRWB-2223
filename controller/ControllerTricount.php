<?php
require_once 'model/User.php';
require_once 'model/Tricount.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerTricount extends Controller {


    public function index() : void {
    
        $member=$this->get_user_or_redirect();
        $nb_subscrptions = 0;
        $tricounts =[];

        //if(isset($_GET["param1"]) && $_GET["parame1"] !=="") {     }

        $tricounts = Tricount::get_tricounts();//to be changed to get_tricount_involved
        foreach($tricounts as $trcount) {
            $nb_subscrptions = $trcount->nb_subscriptions_by_tricount();
        }
        
        (new View("list_tricount"))->show([
            "tricounts" => $tricounts,
            "nb_subscrptions" => $nb_subscrptions
            ]);



    } 

}