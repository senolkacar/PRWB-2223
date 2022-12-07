<?php

require_once "framework/Model.php";


class Repartitions extends Model {

    public function __construct(public int $operationId, public int  $userId, public int $weight  )  {   
    }

    //add

    //delete

    //get weight by opearion and user



}