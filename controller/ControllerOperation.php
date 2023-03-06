<?php


require_once 'model/User.php';
require_once 'model/Tricount.php';
require_once 'model/Repartition.php';
require_once 'model/Operation.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'controller/MyController.php';

class ControllerOperation extends MyController
{


    public function index(): void
    {
        $this->show_operation();
    }

    public function show_operation(): void
    {
        $user = $this->get_user_or_redirect();
        if (isset($_GET["param1"]) && $_GET["param1"] !== "") {
            $id = $_GET["param1"];
            if(!is_numeric($id)) {
                $this->redirect("tricount");
            }          
            
            $operation = Operation::get_operation_by_id($id);
            if($operation) {
                $tricount_id = $operation->tricount->id;
                $tricount = Tricount::get_tricount_by_id($tricount_id);            
                if(!in_array($user,$tricount->get_users_including_creator())){
                 $this->redirect("tricount");
                }
            }else{
                $this->redirect("tricount");
            }           
            
            $repartitions = Repartition::get_repartitions_by_operation($operation);
            
            $operations =  $tricount->get_depenses();
            $pages = count($operations);
            $current_page = 0;
            for ($i = 0; $i < $pages; ++$i) {
                //var_dump($operations[$i]->title);
                //echo "<br>";
                if ($operations[$i]->id == $id)
                    $current_page = $i;
            }
            (new View("show_operation"))->show([
                "operation" => $operation,
                "user" => $user,
                "repartitions" => $repartitions,
                "operations" => $operations,
                "pages" => $pages,
                "current_page" => $current_page
            ]);
        }else{
            $this->redirect("tricount");
        }
        
    }

    public function edit_operation(): void
    {
        $this->add_or_edit_operation("edit");
    }

    public function add_operation(): void
    {
        $this->add_or_edit_operation("add");
    }

    //function that called by add_operation and edit_operation to avoid code duplication
    public function add_or_edit_operation(String $operation_value): void
    {
        $user = $this->get_user_or_redirect();
        $errors_title = [];
        $errors_amount = [];
        $errors_checkbox = [];
        $errors_weights = [];
        $errors_date = [];
        $subscriptions = [];
        $repartitions = [];
        $users=[];
        $payer=null;
        $title= "";
        $amount = "";
        $date = date("Y-m-d");
        $checkboxes = [];
        $weights = [];
        $ids = [];
        $operation = null;
        $is_new_operation = true;
        $operation_name = $operation_value;
        if (isset($_GET["param1"]) && is_numeric($_GET["param1"])) {
            $id = $_GET["param1"]; 
                       
            if ($operation_name == "edit") {
                $header_title = "Edit expense";
                $operation = Operation::get_operation_by_id($id);//if operation id does not exist?
                if ($operation){  //access conditions for edit_operation
                    $tricount = $operation->tricount;
                    if(!in_array($user,$tricount->get_users_including_creator())){
                        $this->redirect("tricount");
                       }
                } else {
                    $this->redirect("tricount");
                }                

                $page_title = "Edit operation";
                $is_new_operation = false;
                $payer = User::get_user_by_id($operation->get_initiator_id());
                $users = $operation->get_users_by_operation_id();
                $repartitions = Repartition::get_repartitions_by_operation($operation);
                $title = $operation->title;
                $amount = round($operation->amount,2);
                $date = $operation->operation_date;
                $ids = [];
                foreach ($repartitions as $repartition) {
                    $weights[] = $repartition->weight;
                    $ids[] = $repartition->user->id;
                    $checkboxes[] = $repartition->user->id;
                }           
            } else {
                if(!($user->is_involved($id) ||$user->is_creator($id))){ // $id is tricount id. access conditions for add_operation
                    $this->redirect("tricount");
                }
                $page_title = "Add operation";
                $header_title = "New expense";
                $tricount = Tricount::get_tricount_by_id($id);
            }
            $subscriptions = User::get_users_by_tricount($tricount);
            $nb_subscriptions = count($subscriptions);

            if(count($_POST)>0){
                if(isset($_POST["payer"])){
                    $payer = User::get_user_by_id($_POST["payer"]);
                }
                if(isset($_POST["title"])){
                    $errors_title = $this->validate_title($_POST["title"]);
                    $title = $_POST["title"];
                }
                if(isset($_POST["amount"])){
                    $errors_amount = $this->validate_amount($_POST["amount"]);
                    $amount = $_POST["amount"];
                }
                if(isset($_POST["weights"])){
                    $errors_weights = $this->validate_weights($_POST["weights"]);
                    $weights = $_POST["weights"];
                }
                if(isset($_POST["date"])){
                    $errors_date = $this->validate_date($_POST["date"]);
                    $date = $_POST["date"];
                } 
                if(isset($_POST["ids"])){
                    $ids = $_POST["ids"];
                }
                if(!isset($_POST["checkboxes"])){
                    $errors_checkbox[]= "You must select at least one user";
                }else{
                    $filteredarrayweight = array_filter($weights,function($value){
                        return $value>0;
                    });
                    $weightsIndex=array_keys($filteredarrayweight);
                    $checkboxesIndex=[];
                    $checkboxes = $_POST["checkboxes"];
                    foreach($checkboxes as $val){
                        foreach($ids as $key=>$value){
                          if($value==$val){
                            $checkboxesIndex[]=$key;
                          }
                        }
                    }
                    if($weightsIndex!=$checkboxesIndex){
                        $errors_checkbox[]= "Ensure that you have correctly checked and fill the weight";
                    }
                
                }

            }
            
            
            $errors = array_merge($errors_title, $errors_amount, $errors_checkbox, $errors_weights, $errors_date);
            if ((count($errors)) == 0 && (count($_POST) > 0)) {
                $operation = $this->add_depense($tricount, $operation);
                $this->add_repartition($operation, $is_new_operation);
                if ($operation_name == "edit") {
                    $this->redirect("operation", "show_operation", $operation->id);
                } else {
                    $this->redirect("tricount", "show_tricount", $tricount->id);
                }
            }
        } else{
            $this->redirect("tricount");
        }

        (new View("add_or_edit_operation"))->show([
            "tricount" => $tricount,
            "payer" => $payer,
            "operation_name" => $operation_name,
            "header_title" => $header_title,
            "page_title" => $page_title,
            "subscriptions" => $subscriptions,
            "nb_subscriptions" => $nb_subscriptions,
            "errors_title" => $errors_title,
            "errors_amount" => $errors_amount,
            "errors_checkbox" => $errors_checkbox,
            "errors_weights" => $errors_weights,
            "errors_date" => $errors_date,
            "operation" => $operation,
            "users" => $users,
            "repartitions" => $repartitions,
            "title"=> $title,
            "amount"=> $amount,
            "date"=> $date,
            "checkboxes"=> $checkboxes,
            "weights"=> $weights,
            "ids"=> $ids
        ]);
    }


    public function add_depense(Tricount $tricount, ?Operation $operation): Operation|false
    {
        if ($operation == null) {
            $operation = new Operation($_POST["title"], $tricount, $_POST["amount"], User::get_user_by_id($_POST["payer"]), $_POST["date"]);
        }else{
        $operation->title = $_POST["title"];
        $operation->amount = $_POST["amount"];
        $operation->operation_date = $_POST["date"];
        $operation->initiator = User::get_user_by_id($_POST["payer"]);
        }
        $operation->persist();
        return $operation;
    }


    public function add_repartition(Operation $operation, bool $is_new_operation): array
    {
        if (!$is_new_operation) {
            $repartition = Repartition::get_repartitions_by_operation($operation);
            foreach ($repartition as $val) {
                $val->delete_repartition();
            }
        } else {
            $repartition = [];
        }
        if (isset($_POST["checkboxes"]) && isset($_POST["weights"]) && isset($_POST["ids"])) {
            $selected_subscriptions = $_POST["checkboxes"];
            $weights = $_POST["weights"];
            $ids = $_POST["ids"];
            $index_weights = 0;
            foreach ($selected_subscriptions as $selected_user) {
                $select_user = User::get_user_by_id($selected_user);
                //var_dump($select_user);
                for ($i = 0; $i < count($ids); ++$i) {
                    if ($ids[$i] == $selected_user)
                        $index_weights = $i;
                }
                $repartition = new Repartition($operation, $select_user, (int)$weights[$index_weights]);
                $repartition->persist();
                $repartitions[] = $repartition;
                $index_weights++;
            }
        }
        return $repartitions;
    }

    function delete_operation(): void
    { 
        $errors=[];
        $user = $this->get_user_or_redirect();
        if (isset($_GET["param1"]) && $_GET["param1"] !== "") {
            $id = $_GET["param1"];
            if(!is_numeric($id)){
                $this->redirect("tricount");
            }
            $operation = Operation::get_operation_by_id($id);
            if (!$operation){
                $this->redirect("tricount");
            }
            if($user->is_involved_in_operation($id)||$user->is_initiator_check($id)){ 
                if(isset($_POST["operationid"])){
                    $operation = Operation::get_operation_by_id($id);
                    $operation->delete_operation();
                    $this->redirect("tricount", "show_tricount", $operation->tricount->id);
                }            
            }else{
                $this->redirect("tricount");
            }
            (new View("delete_operation"))->show(["operation"=>$operation, "errors"=>$errors]);
        }else{
            $this->redirect("tricount");
        }

        
    }






    

}





