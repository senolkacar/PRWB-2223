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

        $tricounts =$member->get_tricounts_with_nb_participants();
        
        
        (new View("list_tricount"))->show([
            "tricounts" => $tricounts,
            "user" => $member
            ]);



    } 

    public function add_tricount():void {

        echo "add tricount";
    }

}