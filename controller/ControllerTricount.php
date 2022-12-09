<?php
require_once 'model/User.php';
require_once 'model/Tricount.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerTricount extends Controller {


    public function index() : void {
        echo "your Tricount";
        $user=$this->get_user_or_redirect();
        $tricounts = [];
        $nbParticipation = 0;

        //if(isset($_GET["param1"]) && $_GET["parame1"] !=="") {     }

        $tricounts = Tricount::get_tricounts();//to be changed to get_tricount_involved
        (new View("list_tricount"))->show([
            "tricounts" => $tricounts,
            "nbParticipation" =>$nbParticipation
        ]);



    } // list tricount

}