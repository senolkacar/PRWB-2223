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
        $user=$this->get_user_or_redirect();//var_dump($user);
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

    public function show_tricount():void {
        $user=$this->get_user_or_redirect();
        if(isset($_GET["param1"]) && $_GET["param1"] !=="") { 
            $id = Tools::sanitize($_GET["param1"]);
            if(!is_numeric($id)){
                $this->redirect("tricount");
            }
            $tricount = Tricount::get_tricount_by_id($id);
            if($tricount==null){
                $this->redirect("tricount");
            }
            $nb_participants = $tricount->get_nb_participants();
            $depenses = $tricount->get_depenses();
            (new View("show_tricount"))->show(["tricount"=>$tricount,"nb_participants"=>$nb_participants,"depenses"=>$depenses]);
        }
        else {
            $this->redirect("tricount");
        }
        }

    public function edit_tricount():void {
        $user=$this->get_user_or_redirect();
        $errors=[];
        if(isset($_GET["param1"]) && $_GET["param1"] !=="") { 
            $title = $_GET["param1"];
            $tricount = Tricount::get_tricount_by_name($title);
            (new View("edit_tricount")) -> show(["title"=>$title,"tricount"=>$tricount,"errors"=>$errors]);
        }
        else {
            $this->redirect("tricount","show_tricount");
        }

       
    }
        



}