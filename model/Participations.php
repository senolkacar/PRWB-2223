<?php

require_once "framework/Model.php";


class Repartitions extends Model {

    public function __construct(public int $tricountId, public int  $userId)  {   
    }

    //add methods when necessary



}