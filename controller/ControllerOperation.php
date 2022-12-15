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
           
        }
        (new View("show_operation")) -> show(["operation"=>$operation,"user"=>$user,"repartitions"=>$repartitions]);

    }

    public function add_operation():void {
        $user=$this->get_user_or_redirect();
        $errors = [];
        if(isset($_GET["param1"]) && $_GET["param1"] !=="") { 
            $id= $_GET["param1"];
            $tricount = Tricount::get_tricount_by_id($id);
           
           
        }
        (new View("add_operation")) -> show(["tricount"=>$tricount,"errors"=>$errors]);
        
    }
    


}