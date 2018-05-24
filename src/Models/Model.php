<?php
namespace DataLogger\RestApp\Chart;

abstract class Model
{
    public function requireTransation($method){
        //Purpose is to allow service to determine whether it must wrap a transaction our model methods.
        if(isset($this->requireTransaction[$method])) return $this->requireTransaction[$method];
        if($parent=get_parent_class($this)) {
            //Doesn't work since $this is always current scope
            return $parent::requireTransation($method);
        }
    }
}