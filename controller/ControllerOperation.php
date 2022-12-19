<?php


require_once 'model/User.php';
require_once 'model/Tricount.php';
require_once 'model/Repartition.php';
require_once 'model/Operation.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerOperation extends Controller {


    public function index() : void {
        $this->show_operation();
     
    } 

    public function show_operation():void {
        $user=$this->get_user_or_redirect();
        if(isset($_GET["param1"]) && $_GET["param1"] !=="") { 
            $id= $_GET["param1"];
            $operation = Operation::get_operation_by_id($id);
           //var_dump(Repartition::include_user($user,$operation));   
           $repartitions = Repartition::get_repartitions_by_operation($operation);
           //var_dump($repartitions);
           $operations=Operation::get_operations_by_tricount( $operation->tricount);
           $pages = count($operations);
           var_dump($pages);
           $current_page=0;
           for($i=0; $i<$pages;++$i){
               if($operations[$i]->id==$id)
               $current_page = $i;
           }
        }
        (new View("show_operation")) -> show(["operation"=>$operation,
                                            "user"=>$user,
                                            "repartitions"=>$repartitions,
                                            "operations"=>$operations,
                                            "pages"=>$pages,
                                            "current_page"=>$current_page]);

    }

    public function add_operation():void {
        $user=$this->get_user_or_redirect();
        $errors_title = [];
        $errors_amount=[];
        $subscriptions=[];
        $nb_subscriotions = count($subscriptions);
        
        if(isset($_GET["param1"]) && $_GET["param1"] !=="") { 
            $id= $_GET["param1"];
            $tricount = Tricount::get_tricount_by_id($id);  
            $subscriptions = User:: get_users_by_tricount( $tricount);               
           
        }
        if(isset($_POST["param1"])&& $_GET["param1"] !==""){
            $id= $_GET["param1"];
            $tricount = Tricount::get_tricount_by_id($id);  
            $subscriptions = User:: get_users_by_tricount( $tricount);
            if(isset($_POST["title"]) && isset($_POST["amount"]) && isset($_POST["date"])
            && isset($_POST["payer"]) ){
                $errors_title = Operation::validate_title($_POST["title"]);
                $errors_amount= Operation::validate_amount($_POST["amount"]);
                if(count($errors_title)==0 && count($errors_amount)==0){
                    $title=$_POST["title"];
                    $amount=$_POST["amount"];
                    $operation_date=$_POST["date"];
                    var_dump($operation_date);//string
                   // $operation_date_string=$operation_date->format('Y-m-d H:i:s');//change to string
                   // var_dump($operation_date_string);
                    $initiator=$user;//payer could be some one else?
                    $operation= new Operation($title,$tricount,$amount,$initiator,$operation_date);
                   $operation->persist();
                   if(isset($_POST["users"]) && isset($_POST["weight"])){
                       $selected_subscriptions = $_POST["users"];
                       $weight = isset($_POST["weight"]);//[]?
                       var_dump($selected_subscriptions);
                       $selected_users = [];
                       foreach($selected_subscriptions as $selected_user){
                           $selected_user = User::get_user_by_name($selected_user);
                           $repartition = new Repartition($operation,$selected_user,$weight);
                           $repartition->persist();
                       }
                   }
                   if (count($_POST) > 0 )
                              //$this ->redirect("tricount","show_tricount", $tricount->id);// $tricount inconnu
                   $this ->redirect("tricount","index");

                }

            }
         }
       
        (new View("add_operation")) -> show(["tricount"=>$tricount,
                                            "errors_title"=>$errors_title,
                                        "errors_amount" =>$errors_amount,
                                        "subscriptions" =>$subscriptions,
                                        "nb_subscriotions" =>$nb_subscriotions                                        
                                        ]);
    }
    


}