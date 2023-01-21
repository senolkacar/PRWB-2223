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
        $title="";
        $description="";
        $errors_title=[];
        $errors_description = [];
        $errors=[];
        if(isset($_POST["title"])&&isset($_POST["description"])){  
            $title = $_POST["title"];
            $description = $_POST["description"];
            $errors_title = Tricount::validate_title($title);
            $errors_description=Tricount::validate_description($description);
            $errors=(array_merge($errors_description,$errors_title));            
           
            if(count($errors)==0){
                $tricount = new Tricount($title,$user,$description);
                $tricount -> persist();
                $this->redirect("tricount","index");     
            }
        }

        (new View("add_tricount")) ->show([
            "title"=>$title,
            "description"=>$description,
            "errors_title"=>$errors_title,
            "errors_description"=>$errors_description]);

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
            $total = Operation::get_total($tricount);
            $mytotal = Operation::get_my_total($tricount,$user);
            (new View("show_tricount"))->show(["tricount"=>$tricount,"nb_participants"=>$nb_participants,"depenses"=>$depenses,"total"=>$total,"mytotal"=>$mytotal]);
        }else{
                $this->redirect("tricount");
            }
        }


    public function edit_tricount():void {
        $user=$this->get_user_or_redirect();
        $title="";
        $description="";
        $errors_title=[];
        $errors_description=[];
        $depenses=[];
        $errors=[];
        $error="";
        if(isset($_GET["param1"]) && $_GET["param1"] !=="") { 
            $id= (int)$_GET["param1"];           
            $tricount = Tricount::get_tricount_by_id($id);
            $title=$tricount->title;
            $description=$tricount->description;
            $subscriptions =$tricount-> get_users_including_creator();  
            $other_users = $tricount->get_users_not_subscriber();        
            //var_dump($other_users);  
            if(isset($_POST["title"]) && isset($_POST["description"]) ) {
                if($user == $tricount -> creator) {
                        $title = $_POST["title"];
                        $description=$_POST["description"];
                        $errors_title = Tricount::validate_title($_POST["title"]);//could have the same name with others
                        $errors_description= Tricount::validate_description($_POST["description"]);
                        $errors=(array_merge($errors_description,$errors_title));

                        if(count($errors) == 0) {
                        $tricount->title = $_POST["title"];
                        $tricount->description = $_POST["description"];
                        $tricount->update();
                        }
    
                        if(count($_POST) > 0 && count($errors) == 0){
                            $this -> redirect("tricount", "show_tricount", $tricount->id);  
                        } 
    
                    }else{
                        $error = "only creator could edit this tricount.";
                    }
            }    
           
        }
        else{
            $this -> redirect("tricount", "show_tricount");  
        }
        (new View("edit_tricount")) -> show(["id"=>$id,
        "tricount"=>$tricount,
        "title"=>$title,
        "description"=>$description,
        "errors_description"=>$errors_description,
        "errors_title"=>$errors_title,
        "subscriptions"=>$subscriptions,
        "depenses"=>$depenses,
        "other_users"=>$other_users,
        "error"=>$error,
        "errors"=>$errors]);       

    }

    public function delete_subsription() :void {
        $user=$this->get_user_or_redirect();

        if(isset($_GET["param1"]) && $_GET["param1"] !=="") { 
            $id= (int)$_GET["param1"];           
            $tricount = Tricount::get_tricount_by_id($id);

            var_dump($tricount);

            if(isset($_POST["delete_member"]) ) {
                if($user == $tricount -> creator) {                       
                    $subscriber = User::get_user_by_id($_POST["delete_member"]);
                    if($subscriber ) {
                        Subscription::delete_subscription($tricount, $subscriber);//delete
                        $this -> redirect("tricount", "edit_tricount", $tricount->id);
                    }

                }else{
                        throw new Exception("only creator could edit this tricount.");
                }
            } 
        }


    }

    public function add_subsription() :void {
        $user=$this->get_user_or_redirect();

        if(isset($_GET["param1"]) && $_GET["param1"] !=="") { 
            $id= (int)$_GET["param1"];           
            $tricount = Tricount::get_tricount_by_id($id);

            var_dump($tricount);

            if(isset($_POST["subscriber"]) ) {
                if($user == $tricount -> creator) {                       
                    $subscriber = User::get_user_by_name($_POST["subscriber"]);//name unique for users
                    if($subscriber ) {
                        Subscription::persist($subscriber, $tricount);
                        $this -> redirect("tricount", "edit_tricount", $tricount->id);
                    }

                }else{
                        throw new Exception("only creator could edit this tricount.");
                }
            }  
        }
    
    }
    
public function show_balance():void{
    $user=$this->get_user_or_redirect();
    if(isset($_GET["param1"]) && $_GET["param1"] !==""){
        $id = $_GET["param1"];
        if(!is_numeric($id)){
            $this->redirect("tricount");
        }
        $tricount = Tricount::get_tricount_by_id($id);
        if($tricount==null){
            $this->redirect("tricount");
        }
        $balance = $tricount->get_balance_by_tricount();
        $user_name = $user->full_name;
        
        (new View("show_balance"))->show(["tricount"=>$tricount,"balance"=>$balance,"user"=>$user_name,"id"=>$id]);
    }else{
        $this->redirect("tricount");
    }
        
}

    public function delete() : void {//main
        $user=$this->get_user_or_redirect();//
        $errors = [];
        if(isset($_GET["param1"]) && $_GET["param1"] !==""){
            $id = $_GET["param1"];
            $tricount = Tricount::get_tricount_by_id($id);
            
            if(isset($_POST["id_tricount"])){
                if($tricount->creator == $user){
                    $tricount = Tricount::get_tricount_by_id($id);
                    $tricount ->delete($user);
                    if ($tricount) {
                         $this->redirect("tricount", "index");
                     } else {
                            throw new Exception("Wrong/missing ID or action no permited");
                     }

                }else{
                    $errors[]="You may not creator of the tricount.";
                }
                
            }
        }

        (new View("delete_tricount"))->show(["tricount"=>$tricount, "errors"=>$errors]);


        
    }



}