<?php
/**
 * Copyright 2015 David Rodal
 * User: David Markarian Rodal
 * Date: 6/14/15
 * Time: 5:37 PM
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class LandAirUnit extends BaseUnit implements JsonSerializable
{

    public $origStrength;
    public $torpedoStrength;
    public $unitAirStrength;
    public $defStrength;
    public $hits = 0;
    public $pDamage = 0;
    public $wDamage = 0;


    public function getUnmodifiedStrength(){
        return  $this->origStrength;
    }


    public function getUnmodifiedAirStrength(){
        return  $this->unitAirStrength;
    }

    public function __get($name)
    {
        $b = Battle::getBattle();
        if($name == "torpedoStrength"){
            echo $name;
        }
        if ($name !== "strength" && $name !== "torpedoStrength" && $name !== "attStrength" && $name !== "defStrength") {
            return false;
        }
        $strength = $this->origStrength;


        if($name === "strength" && ($b->gameRules->phase == BLUE_TORP_COMBAT_PHASE || $b->gameRules->phase == RED_TORP_COMBAT_PHASE)){
            $strength = $this->torpedoStrength;
        }


        foreach ($this->adjustments as $adjustment) {
            switch ($adjustment) {
                case 'floorHalf':
                    $strength = floor($strength / 2);
                    break;
                case 'half':
                    $strength = $strength / 2;
                    break;
                case 'double':
                    $strength = $strength * 2;
                    break;
            }
        }
        return $strength;
    }


    function set( $unitName, $unitForceId, $unitHexagon, $unitImage, $gunneryStrength, $range,$defenseStrength, $torpedoStrength,  $unitMaxMove, $facing, $unitStatus, $unitReinforceZone, $unitReinforceTurn,  $nationality = "neutral", $forceMarch, $class, $unitDesig)
    {

        $this->dirty = true;
        $this->name = $unitName;
        $this->forceId = $unitForceId;
        $this->class = $class;
        if($class === "air"){
            $this->noZoc = true;
        }

        $this->hexagon = new Hexagon($unitHexagon);
        $this->unitStrength  = $gunneryStrength;


        /* blah! this can get called from the constructor of Battle. so we can't get ourselves while creating ourselves */
//        $battle = Battle::getBattle();
//        $mapData = $battle->mapData;

        $battle = Battle::getBattle();
        $mapData = $battle->mapData;
//        $mapData = MapData::getInstance();

        $mapHex = $mapData->getHex($this->hexagon->getName());
        if ($mapHex) {
            $mapHex->setUnit($this->forceId, $this);
        }
        $this->image = $unitImage;


//        $this->strength = $isReduced ? $unitMinStrength : $unitMaxStrength;
        $this->maxMove = $unitMaxMove;
        $this->moveAmountUnused = $unitMaxMove;
        $this->origStrength = $gunneryStrength;
        $this->status = $unitStatus;
        $this->torpedoStrength = $torpedoStrength;
        $this->facing = $facing;
        $this->defStrength = $defenseStrength;
        $this->moveAmountUsed = 0;
        $this->reinforceZone = $unitReinforceZone;
        $this->reinforceTurn = $unitReinforceTurn;
        $this->combatNumber = 0;
        $this->combatIndex = 0;
        $this->combatOdds = "";
        $this->moveCount = 0;
        $this->retreatCountRequired = 0;
        $this->combatResults = NR;
        $this->range = $range;
        $this->nationality = $nationality;
        $this->forceMarch = $forceMarch;
        $this->unitDesig = $unitDesig;
        $this->hits = 0;
        $this->wDamage = 0;
        $this->pDamage = 0;

    }

    function damageUnit($kill = false)
    {
        $battle = Battle::getBattle();

        switch($kill){
            case P:
                $this->pDamage++;

                $this->hits++;
                break;
            case W:
                $this->wDamage++;

                $this->hits++;
                break;
            case PW:
                $this->wDamage++;
                $this->pDamage++;
                $this->hits += 2;
        }
        if($this->pDamage == 1){
            $this->maxMove /= 2;
        }
        if($this->pDamage > 1){
            $this->maxMove = 0;
        }
        if($this->wDamage == 1){
            $this->origStrength /= 2;
            $this->torpedoStrength /= 2;
        }
        if($this->wDamage > 1){
            $this->origStrength = 0;
            $this->torpedoStrength = 0;
        }
        if($this->hits >= 3){
            $this->status = STATUS_ELIMINATING;
        }
        return true;
    }

    function __construct($data = null)
    {
        if ($data) {
            foreach ($data as $k => $v) {
                if ($k == "hexagon") {
                    $this->hexagon = new Hexagon($v);
//                    $this->hexagon->parent = $data->parent;
                    continue;
                }
                $this->$k = $v;
            }
            $this->dirty = false;
        } else {
            $this->adjustments = new stdClass();
        }
    }


    public function fetchData(){
        $mapUnit = new StdClass();
        $mapUnit->parent = $this->hexagon->parent;
        $mapUnit->moveAmountUsed = $this->moveAmountUsed;
        $mapUnit->maxMove = $this->maxMove;
        $mapUnit->strength = $this->strength;
        $mapUnit->airStrength = $this->unitAirStrength;
        $mapUnit->class = $this->class;
        $mapUnit->id = $this->id;
        $mapUnit->defenseStrength = $this->defenseStrength;
        $mapUnit->torpedoStrength = $this->torpedoStrength;
        $mapUnit->facing = $this->facing;
        $mapUnit->wDamage = $this->wDamage;
        $mapUnit->pDamage = $this->pDamage;
        return $mapUnit;
    }
}


class UnitFactory {
    public static $id = 0;
    public static $injector;
    public static function build($data = false){

        $sU =  new LandAirUnit($data);
        if($data === false){
            $sU->id = self::$id++;
        }
        return $sU;
    }
    public static function create( $unitName, $unitForceId, $unitHexagon, $unitImage, $unitGunneryStrength, $range, $defense, $unitTorpedoStrength, $unitMaxMove, $facing, $unitStatus, $unitReinforceZone, $unitReinforceTurn, $nationality = "neutral", $class, $unitDesig = ""){
        $unit = self::build();
        $unit->set($unitName, $unitForceId, $unitHexagon, $unitImage, $unitGunneryStrength,$range, $defense,  $unitTorpedoStrength, $unitMaxMove, $facing, $unitStatus, $unitReinforceZone, $unitReinforceTurn,  $nationality, true, $class, $unitDesig);
        self::$injector->injectUnit($unit);
    }

}