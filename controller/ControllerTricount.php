<?php


require_once 'model/User.php';
require_once 'model/Tricount.php';
require_once 'model/Repartition.php';
require_once 'model/Operation.php';
require_once 'controller/MyController.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerTricount extends MyController {


    public function index() : void { 
        $member=$this->get_user_or_redirect();
        $tricounts =[];
        $tricounts =$member->get_tricounts_involved();
        (new View("list_tricount"))->show([
            "tricounts" => $tricounts
            ]);
    } 

    public function tricount_exists_service(): void{
        $this->get_user_or_redirect();//get_user_or_false();
        $res = "false";
     
        if(isset(($_POST["creator"])) && isset($_POST["title"]) && isset($_POST["mode"]) && isset($_POST["tricount"])){
            $mode = $_POST["mode"];
            if(($mode != "edit" && $mode != "add") || !is_numeric($_POST["creator"]) || !is_numeric($_POST["tricount"])){
                $this->redirect("tricount","index");
            }
            $id = $_POST["creator"];
            $user = User::get_user_by_id($id);
            $title = $_POST["title"];
            $errors=[];
            $errors = $this->validate_title($title);
            
            
            if(count($errors)==0&&$user!=null){
                if($mode == "add"){
                    if(Tricount::title_creator_existe($user,$title)){
                        $res = "true";
                    }
                }else if($mode == "edit"){
                    $tricount = Tricount::get_tricount_by_title_creator($user,$title);
                    if($tricount!=false && $tricount->id != $_POST["tricount"]){
                        $res = "true";
                    }
                }
            }
    
        echo $res; 
    }else{
        $this->redirect("tricount","index");
    }
    }

    public function add_tricount():void {
        $user=$this->get_user_or_redirect();
        //var_dump($user);
        $title="";
        $description="";
        $errors_title=[];
        $errors_description = [];
        $errors=[];
        $justvalidate = $this->isJustValidateOn();
        if(isset($_POST["title"])&&isset($_POST["description"])){  
            $title = $_POST["title"];
            $description = $_POST["description"];
            $tricount = new Tricount($title,$user,$description);
            $errors_title = Tricount::validate_title($user,$tricount);
            $errors_description=Tricount::validate_description($description);
            $errors=(array_merge($errors_description,$errors_title));            
           
            if(count($errors)==0){
               // $user= User::get_user_by_mail($user->mail);             
                $tricount -> persist();
                $this->redirect("tricount","index");     
            }
        }

        (new View("add_tricount")) ->show([
            "user"=>$user,
            "title"=>$title,
            "justvalidate"=>$justvalidate,
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
            if(!$user->is_involved($id)&&!$user->is_creator($id)){
                $this->redirect("tricount");
            }
            $tricount = Tricount::get_tricount_by_id($id);
            if($tricount==null){
                $this->redirect("tricount");
            }
            (new View("show_tricount"))->show(["tricount"=>$tricount,"user"=>$user]);
        }else{
                $this->redirect("tricount");
            }
        }


    public function edit_tricount():void {
        $user=$this->get_user_or_redirect();
        $title="";
        $justvalidate = $this->isJustValidateOn();
        $description="";
        $errors_title=[];
        $errors_description=[];
        $errors=[];
        $error="";
        $subscribers_json;
        $other_users_json;

        if(isset($_GET["param1"]) && is_numeric($_GET["param1"]) ) { 
            $id= (int)$_GET["param1"];                             
            $tricount = Tricount::get_tricount_by_id($id);

            if($tricount){
                if(!in_array($user,$tricount->get_users_including_creator())) {
                    $this->redirect("tricount");
                }
            }else {// if $tricount doesn't exist
                $this->redirect("tricount");
            } 
            
            $subscribers_json = $tricount ->get_tricount_subscribers_as_json($user);
            $other_users_json = $tricount ->get_users_not_tricount_subscribers_as_json($user);

            $title=$tricount->title;
            $description=$tricount->description;
            if(isset($_POST["title"]) && isset($_POST["description"]) ) {
                        $title = $_POST["title"];
                        $description=$_POST["description"];

                        $original_title = $tricount->title;
                        $tricount->title=$title;//in order to call validate_title(). if title isn't valid the value will be changed back to the original one

                        $errors_title =Tricount::validate_title($user,$tricount);
                        $errors_description= Tricount::validate_description($_POST["description"]);

                        $errors=(array_merge($errors_description,$errors_title));

                        if(count($errors) == 0) {
                        $tricount->title = $_POST["title"];
                        $tricount->description = $_POST["description"];
                        $tricount->update();
                        }else{
                            $tricount->title=$original_title;
                        }
    
                        if(count($_POST) > 0 && count($errors) == 0){
                            $this -> redirect("tricount", "show_tricount", $tricount->id);  
                        }     
            }    
           
        }
        else{
            $this->redirect("tricount");
        }

        (new View("edit_tricount")) -> show(["id"=>$id,
                                            "tricount"=>$tricount,
                                            "title"=>$title,
                                            "user"=>$user,
                                            "description"=>$description,
                                            "errors_description"=>$errors_description,
                                            "errors_title"=>$errors_title,
                                            "error"=>$error,
                                            "justvalidate"=>$justvalidate,
                                            "subscribers_json"=>$subscribers_json,
                                            "other_users_json"=>$other_users_json,
                                            "errors"=>$errors]);       

    }

    private function save_edit_tricount(int $id, string $title, string $description): bool {
        $user = $this->get_user_or_redirect();
        $tricount = Tricount::get_tricount_by_id($id);

        if ($tricount) {
            if (!in_array($user, $tricount->get_users_including_creator())) {
                return false; 
            } else {
                $original_title = $tricount->title;
                $tricount->title = $title; // in order to call validate_title(). If title isn't valid, the value will be changed back to the original one
    
                $errors_title = Tricount::validate_title($user, $tricount);
                $errors_description = Tricount::validate_description($description);
    
                $errors = (array_merge($errors_description, $errors_title));
    
                if (count($errors) == 0) {
                    $tricount->title = $title;
                    $tricount->description = $description;
                    $tricount->update();
                } else {
                    $tricount->title = $original_title;
                    return false; // Error occurred during processing
                }
    
                return true; // Successfully completed
            }
        } else { // if $tricount doesn't exist
            return false; 
        }
    }

    public function get_tricount_subscrier_service() : void {
        $user = $this->get_user_or_redirect();//it's better to user get_user_or_false() if the method is called by the server
        $tricount = $this->get_tricount($user);
        $subscribers_json = $tricount ->get_tricount_subscribers_as_json($user);
        echo $subscribers_json;
    }  

    public function get_user_not_tricount_subscrier_service() : void {
        $user = $this->get_user_or_redirect();
        $tricount = $this->get_tricount($user);
        $other_users_json = $tricount ->get_users_not_tricount_subscribers_as_json($user);
        echo $other_users_json;
    }  

    private function get_tricount(User $user) : Tricount {
        if(isset($_GET["param1"]) && is_numeric($_GET["param1"]) ) { 
            $id= (int)$_GET["param1"];                             
            $tricount = Tricount::get_tricount_by_id($id);
            if($tricount==null || (!$user->is_involved($id)&&!$user->is_creator($id))){
                $this->redirect("tricount");
            } else
                return $tricount;
        }else
            $this->redirect("tricount");
    }

    public function add_subscription_service(): void {
        $user = $this->get_user_or_redirect();
        $tricount = $this->get_tricount($user);
        $subscriber = $this->new_subscription();
        echo $subscriber ? "true" : "false";
    }

     public function delete_subscription_service(): void {
        $user = $this->get_user_or_redirect();
        $tricount = $this->get_tricount($user);
        $subscriber = $this->remove_subscription();
        echo $subscriber ? "true" : "false";

    }

    public function delete_tricount_service():void {
        $user = $this->get_user_or_redirect();
        $tricount = $this->get_tricount($user);
        $tricount = $this->delete_tricount();
        echo $tricount ? "true" : "false";

    }

    private function delete_tricount():Tricount|false{ //to modify delete()
        $user = $this->get_user_or_false();
        if(isset($_GET["param1"]) && is_numeric($_GET["param1"])){
            $id = $_GET["param1"];
            $tricount = Tricount::get_tricount_by_id($id);         
            $tricount ->delete($user);
            if ($tricount) {
                return $tricount;
            }              
            
        }
        return false;

    }


    private function remove_subscription() :User|false {
        $user = $this->get_user_or_redirect();
        if(isset($_GET["param1"]) &&is_numeric($_GET["param1"])) { 
            $id=(int)$_GET["param1"];            
            $tricount = Tricount::get_tricount_by_id($id);

            if(isset($_POST["delete_member"]) && is_numeric($_POST["delete_member"])) {                   
                $subscriber = User::get_user_by_id($_POST["delete_member"]);
                if($subscriber) {
                    Subscription::delete_subscription($tricount, $subscriber);
                    return $subscriber;
                }
            }
        }

        return false;
        
    }

    private function new_subscription() :User|false {
        $user = $this->get_user_or_redirect();
        if(isset($_GET["param1"]) &&is_numeric($_GET["param1"])) { 
            $id=(int)$_GET["param1"];            
            $tricount = Tricount::get_tricount_by_id($id);

            if(isset($_POST["subscriber"]) && is_numeric($_POST["subscriber"])) {                   
                $subscriber = User::get_user_by_id($_POST["subscriber"]);
                if($subscriber) {
                    Subscription::persist($subscriber, $tricount);
                    return $subscriber;
                }
            }
        }

        return false;
        
    }


    public function delete_subscription() :void { // refactoring 
        $user=$this->get_user_or_redirect();

        if(isset($_GET["param1"]) &&is_numeric($_GET["param1"])) { 
            $id=(int)$_GET["param1"];            
            $tricount = Tricount::get_tricount_by_id($id);

            if(isset($_POST["delete_member"]) && is_numeric($_POST["delete_member"])) {                   
                    $subscriber = User::get_user_by_id($_POST["delete_member"]);
                    if($subscriber) {
                        Subscription::delete_subscription($tricount, $subscriber);
                        $this -> redirect("tricount", "edit_tricount", $tricount->id);
                    }
            } else {
                $this -> redirect("tricount", "edit_tricount", $tricount->id);
            }
        } else {
            $this->redirect("tricount");
        }


    }

    public function add_subscription() :void {

        $user=$this->get_user_or_redirect();

        if(isset($_GET["param1"]) && is_numeric($_GET["param1"])) { 
            $id= (int)$_GET["param1"];           
            $tricount = Tricount::get_tricount_by_id($id);

            if(isset($_POST["subscriber"]) && is_numeric($_POST["subscriber"])) {                           
                    $subscriber = User::get_user_by_id($_POST["subscriber"]);//name not unique for users. use user id instead of full name to get user
                    if($subscriber ) {
                        Subscription::persist($subscriber, $tricount);
                        $this -> redirect("tricount", "edit_tricount", $tricount->id);
                    }                    

            } else{
                $this -> redirect("tricount", "edit_tricount", $tricount->id);
            }  
        } else {
            $this->redirect("tricount");
        }
        
    
    }
    
public function show_balance():void{
    $user=$this->get_user_or_redirect();
    if(isset($_GET["param1"]) && $_GET["param1"] !==""){
        $id = $_GET["param1"];
        if(!is_numeric($id)){
            $this->redirect("tricount");
        }
        if(!($user->is_involved($id) || $user->is_creator($id))){
            $this->redirect("tricount");
        }
        $tricount = Tricount::get_tricount_by_id($id);
        if($tricount==null){
            $this->redirect("tricount");
        }
        
        (new View("show_balance"))->show(["tricount"=>$tricount,"user"=>$user]);
    }else{
        $this->redirect("tricount");
    }
        
}

    public function delete() : void {
        $user=$this->get_user_or_redirect();
        $errors = [];
        if(isset($_GET["param1"]) && is_numeric($_GET["param1"])){
            $id = $_GET["param1"];
            if(!$user->is_involved($id)&&!$user->is_creator($id)){
                $this->redirect("tricount");
            }
            $tricount = Tricount::get_tricount_by_id($id);            
            if(isset($_POST["id_tricount"])){
                    //$tricount = Tricount::get_tricount_by_id($id);
                    $tricount = Tricount::get_tricount_by_id($_POST["id_tricount"]);
                    $tricount ->delete($user);
                    if ($tricount) {
                         $this->redirect("tricount", "index");
                     } else {
                        $errors[]="Wrong/missing ID or action no permited";
                        //throw new Exception("Wrong/missing ID or action no permited");
                     }                
            }
        }else
            $this->redirect("tricount");

        (new View("delete_tricount"))->show(["tricount"=>$tricount, "errors"=>$errors]);
        
    }



}