<?php


namespace Wargame;


class AreaModel  implements \JsonSerializable
{
    public $areas;

    function __construct($data = false)
    {
        if($data){

            $this->areas = $data->areas;
        }else{
            $this->areas = new \stdClass();
        }

    }

    function jsonSerialize()
    {
        return $this;
    }

    function addArea($name, $obj){
        if(!property_exists($this->areas, $name)){
            $this->areas->$name = $obj;
        }else{
            throw(new Exception("Property already defined"));
        }
    }


    function getArea($name)
    {
        if(property_exists($this->areas, $name)){
            return $this->areas->$name;
        }else{
            throw(new Exception("Property already defined"));
        }
    }
}