<?php


namespace Wargame;


class AreaModel  implements \JsonSerializable
{
    public $areas;
    public $borders;

    function __construct($data = false)
    {
        if($data){

            $this->areas = $data->areas;
            $this->borders = $data->borders;
        }else{
            $this->areas = new \stdClass();
            $this->borders = new \stdClass();
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

    function addBorder($name, $obj){
        if(!property_exists($this->borders, $name)){
            $this->borders->$name = $obj;
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
    function getBorder($name)
    {
        if(property_exists($this->borders, $name)){
            return $this->borders->$name;
        }else{
            throw(new Exception("Property already defined"));
        }
    }
}