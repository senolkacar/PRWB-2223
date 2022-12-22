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
        $subscriptions=[];
        $operation=null;
        $operation_name = $operation_value;
        $nb_subscriptions = count($subscriptions);
        if(isset($_GET["param1"])&& $_GET["param1"]!=="")
        {
            $id=$_GET["param1"];
            if($operation_name == "edit")
            {
                $header_title = "Edit expense";
                $operation=Operation::get_operation_by_id($id);
                $tricount=$operation->tricount;
                $page_title="Edit operation";
            }else{
                $page_title="Add operation";
                $header_title = "New expense";
                $tricount = Tricount::get_tricount_by_id($id);
            }
            $subscriptions = User::get_users_by_tricount($tricount);
            if(isset($_POST["title"]) && isset($_POST["amount"]) && isset($_POST["date"])//body
                && isset($_POST["payer"]) ){
                    $errors_title = Operation::validate_title($_POST["title"]);
                    $errors_amount= Operation::validate_amount($_POST["amount"]);
                    $errors = array_merge($errors_title,$errors_amount);
                    if(count($errors)==0){
                        $operation=$this->add_depense($tricount,$operation);
                        $this->add_repartition($operation);
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
                                                    "operation"=>$operation,]);
    }

    public function add_depense(Tricount $tricount,?Operation $operation):Operation|false {
        $title = $_POST["title"];
        $amount = $_POST["amount"];
        $operation_date=$_POST["date"];
        $initiator=User::get_user_by_name($_POST["payer"]);
        if($operation!==null){
            $operation->title=$title;
            $operation->amount=$amount;
            $operation->operation_date=$operation_date;
            $operation->initiator=$initiator;
        }else{
            $operation=new Operation($title,$tricount,$amount,$initiator,$operation_date);
        }
        $operation->persist();                   
        return $operation;
                         
    }


    public function add_repartition(Operation $operation):array{
         $repartitions =[];
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
                    $index_weights=$i;
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