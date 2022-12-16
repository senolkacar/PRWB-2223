<?php


require_once 'model/User.php';
require_once 'model/Tricount.php';
require_once 'model/Repartition.php';
require_once 'model/Operation.php';
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
            $errors=$tricount->validate();
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
            $id = $_GET["param1"];
            if(!is_numeric($id)){
                $this->redirect("tricount");
            }
            $tricount = Tricount::get_tricount_by_id($id);
            if($tricount==null){
                $this->redirect("tricount");
            }
            $nb_participants = $tricount->get_nb_participants();
            $depenses = $tricount->get_depenses();
            $total = 0;
            $mytotal = 0;
            foreach($depenses as $operation){
                $total += $operation["amount"];
                $total_weight = Repartition::get_total_weight_by_operation($operation["id"]);
                $weight = Repartition::get_user_weight($user->id, $operation["id"]);
                $mytotal+= $operation["amount"] * $weight / $total_weight;
            }
            $total = number_format($total, 2, '.', '');
            $mytotal = number_format($mytotal, 2, '.', '');
            (new View("show_tricount"))->show(["tricount"=>$tricount,"nb_participants"=>$nb_participants,"depenses"=>$depenses,"total"=>$total,"mytotal"=>$mytotal]);
        }
        else {
            $this->redirect("tricount");
        }
        }


    public function edit_tricount():void {
        $user=$this->get_user_or_redirect();
        $errors=[];
        $success="";
        if(isset($_GET["param1"]) && $_GET["param1"] !=="") { 
            global $id,$subscriptions,$other_users;
            $id= (int)$_GET["param1"];
            var_dump($id);
            global $tricount;
            $tricount = Tricount::get_tricount_by_id($id);
            $subscriptions =$tricount-> get_subscriptions();  
            $other_users = $tricount->get_users_not_subscriber();        
            //var_dump($other_users);  
            if(isset($_POST["title"]) && isset($_POST["description"]) && isset($_POST["subscriber"]) ) {
                if($user == $tricount -> creator) {
                        $errors = Tricount::validate_title($_POST["title"]);//could have the same name with others
                        if(count($errors) == 0) {
                        $tricount->title = $_POST["title"];
                        $tricount->description = $_POST["description"];
                        $subscriber = User::get_user_by_name($_POST["subscriber"]);//name unique for users
                        var_dump($_POST["subscriber"]);
                        var_dump($subscriber);//false
                        $tricount->update();
                        if($subscriber != false) {
                            Subscription::persist($subscriber, $tricount);
                        }
                        }
        
                        if(count($_POST) > 0 && count($errors) == 0){
                            $this -> redirect("tricount", "show_tricount", $tricount->id);  
                           // $success = "The tricount has been successfully updated.";
                        } 
    
                    }else{
                        $errors[] = "only creator could edit this tricount.";
                        //redirect ?
                    }
        }    
    }
    else{
        $this -> redirect("tricount", "show_tricount");  
    }
    (new View("edit_tricount")) -> show(["id"=>$id,
    "tricount"=>$tricount,
    "subscriptions"=>$subscriptions,
    "other_users"=>$other_users,
    "success"=>$success,
    "errors"=>$errors]);       

}

    public function notificaiton():void{
        
            
    }

   
    
    public function show_balance():void{
        $user=$this->get_user_or_redirect();
        if(isset($_GET["param1"]) && $_GET["param1"] !==""){
            $id = $_GET["param1"];
            if(!is_numeric($id)){
                $this->redirect("tricount");
            }
            $tricount = Tricount::get_tricount_by_id($id);
            (new View("show_balance"))->show(["tricount"=>$tricount]);
        }else{
            $this->redirect("tricount");
        }
            
    }



}