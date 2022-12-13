<?php

require_once "framework/Model.php";


class Repartition extends Model {

    public function __construct(public Operation $operation, public User  $user, public int $weight  )  {   
    }

    //add

    //delete

    //get weight by opearion and user



}