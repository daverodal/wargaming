<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 5/30/16
 * Time: 4:19 PM
 */

namespace Wargame;


trait UnitAdjustment
{
    public $adjustments;

    function addAdjustment($name, $adjustment)
    {
        if(empty($this->adjustments)){
            $this->adjustments = new \stdClass();
        }
        $this->adjustments->$name = $adjustment;
    }

    function removeAdjustment($name)
    {
        if(empty($this->adjustments)){
            return;
        }
        unset($this->adjustments->$name);
    }

    public function removeAllAdjustments(){
        $this->adjustments = new \stdClass();
    }

    public function getCombatAdjustments($value)
    {
        if(empty($this->adjustments)){
            return $value;
        }
        foreach ($this->adjustments as $name => $adjustment) {
            switch ($adjustment) {
                case 'floorHalf':
                    $value = floor($value / 2);
                    break;
                case 'half':
                    $value = $value / 2;
                    break;
                case 'double':
                    $value = $value * 2;
                    break;
                case 'zero':
                    $value = 0;
                    break;
            }
        }
        return $value;
    }

    public function getMovementAdjustments($value)
    {
        if(empty($this->adjustments)){
            return $value;
        }
        foreach ($this->adjustments as $name => $adjustment) {
            switch ($adjustment) {
                case 'floorHalfMovement':
                    $value = floor($value / 2);
                    break;
                case 'halfMovement':
                    $value = $value / 2;
                    break;
                case 'oneMovement':
                    $value = 1;
                    break;
            }
        }
        return $value;
    }
}