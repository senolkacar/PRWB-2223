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

    public function add_tricount():void {
        $user=$this->get_user_or_redirect();
        var_dump($user);
        $errors = [];
        if(isset($_POST["title"])&&isset($_POST["description"])){
         
           
            $title = $_POST["title"];
            $description = $_POST["description"];  
            $tricount = new Tricount($title,$user,$description);
            // $errors = $message->validate();
            //$errors = array_merge($errors,$title->validate_title($_POST["title"]));
           // $errors = array_merge($errors,$description->validate_description($_POST["description"]));
            if(count($errors)==0){
                $tricount -> persist();
                $this->redirect("tricount","index");     
            }
        }

        (new View("add_tricount")) ->show(["errors"=>$errors]);

    }



}