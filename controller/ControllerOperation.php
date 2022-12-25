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

    public function edit_operation():void{
        $this->add_or_edit_operation("edit");
    }

    public function add_operation():void {
        $this->add_or_edit_operation("add");
    }

    //function that called by add_operation and edit_operation to avoid code duplication
    public function add_or_edit_operation(String $operation_value):void{
        $user=$this->get_user_or_redirect();
        $errors_title=[];
        $errors_amount=[];
        $errors_weight=[];
        $subscriptions=[];
        $repartitions=[];
        $users=[];
        $operation=null;
        $is_new_operation=true;
        $operation_name = $operation_value;
        if(isset($_GET["param1"])&& $_GET["param1"]!=="")
        {
            $id=$_GET["param1"];
            if($operation_name == "edit")
            {
                $header_title = "Edit expense";
                $operation=Operation::get_operation_by_id($id);
                $tricount=$operation->tricount;
                $page_title="Edit operation";
                $is_new_operation=false;
                $users=$operation->get_users_by_operation_id();
                $repartitions=Repartition::get_repartitions_by_operation($operation);
            }else{
                $page_title="Add operation";
                $header_title = "New expense";
                $tricount = Tricount::get_tricount_by_id($id);
            }
            $subscriptions = User::get_users_by_tricount($tricount);
            $nb_subscriptions = count($subscriptions);
            if(isset($_POST["title"]) && isset($_POST["amount"]) && isset($_POST["date"])//body
                && isset($_POST["payer"]) ){
                    $errors_title = Operation::validate_title($_POST["title"]);
                    $errors_amount= Operation::validate_amount($_POST["amount"]);
                    $errors = array_merge($errors_title,$errors_amount);
                    if( isset($_POST["weights"]) ){                       
                        $weights = $_POST["weights"];
                        $total_weight = 0;
                        foreach($weights as $weight) {
                            $total_weight = $total_weight + (int)$weight;
                        }
                    }
                    if  ($total_weight == 0){
                        $errors_weight[] = "total weight couldn't be 0";
                    }
                        
                    if(count($errors)==0 && count($errors_weight) == 0){
                        $operation=$this->add_depense($tricount,$operation);
                        $this->add_repartition($operation,$is_new_operation);
                        if($operation_name == "edit"){
                            $this->redirect("operation","show_operation",$operation->id);
                        }else{
                            $this->redirect("tricount","show_tricount",$tricount->id);
                        }
                    }
            }
        }
        (new View("add_or_edit_operation"))->show(["tricount"=>$tricount,
                                                    "operation_name"=>$operation_name,
                                                    "header_title"=>$header_title,
                                                    "page_title"=>$page_title,
                                                    "subscriptions"=>$subscriptions,
                                                    "nb_subscriptions"=>$nb_subscriptions,
                                                    "errors_title"=>$errors_title,
                                                    "errors_amount"=>$errors_amount,
                                                    "errors_weight" =>$errors_weight,
                                                    "operation"=>$operation,
                                                    "users"=>$users,
                                                    "repartitions"=>$repartitions]);
    }

    public function add_depense(Tricount $tricount,?Operation $operation):Operation|false {
        if($operation==null){
            $operation=new Operation($_POST["title"],$tricount,$_POST["amount"],User::get_user_by_name($_POST["payer"]),$_POST["operation_date"]);
        }
        $operation->title = $_POST["title"];
        $operation->amount = $_POST["amount"];
        $operation->operation_date=$_POST["date"];
        $operation->initiator=User::get_user_by_name($_POST["payer"]);
        $operation->persist();                   
        return $operation;
                         
    }


    public function add_repartition(Operation $operation,bool $is_new_operation):array{
        if(!$is_new_operation){
            $repartition = Repartition::get_repartitions_by_operation($operation);
            foreach($repartition as $repartition){
                $repartition->delete_repartition();
            }
        }else{
            $repartition=[];
        }
        if(isset($_POST["users"]) && isset($_POST["weights"]) && isset($_POST["ids"])){
            $selected_subscriptions = $_POST["users"];
            $weights = $_POST["weights"];
            $ids=$_POST["ids"];
            $index_weights=0;
            foreach($selected_subscriptions as $selected_user){
                $select_user = User::get_user_by_id($selected_user);
                //var_dump($select_user);
                for($i=0;$i <count($ids);++$i){
                    if($ids[$i]==$selected_user)
                    $index_weights=$i;//if (int)$weights[$index_weights] = 0 ?
                }
               
                $repartition = new Repartition($operation,$select_user,(int)$weights[$index_weights]);
                $repartition->persist();
                $repartitions[]=$repartition;
                $index_weights++;
            }
        }
        return $repartitions;

    }





    


}