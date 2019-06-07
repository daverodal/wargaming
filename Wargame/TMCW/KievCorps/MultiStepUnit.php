<?php
/**
 * Copyright 2016 David Rodal
 * User: David Markarian Rodal
 * Date: 2/28/16
 * Time: 1:34 PM
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

namespace Wargame\TMCW\KievCorps;

use Wargame\Hexagon;
use Wargame\Battle;
use stdClass;


class MultiStepUnit extends \Wargame\MovableUnit  implements \JsonSerializable
{
    public $steps;
    public $origSteps;
    public $origStrength;
    public $range = 1;
    public $adjustments;
    public $supplied = true;
    public $forceMarch = true;



    public function jsonSerialize()
    {
        if (is_object($this->hexagon)) {
            if ($this->hexagon->name) {
                $this->hexagon = $this->hexagon->getName();
            } else {
                $this->hexagon = $this->hexagon->parent;
            }
        }
        return $this;
    }


    public function resetUnit(){
        parent::resetUnit();
        $this->steps = 1;
        $this->supplied = true;
        $this->forceMarch = true;
    }

    public function getUnmodifiedStrength(){

        $strength = $this->origStrength;

        $strength = floor($strength * $this->steps / $this->origSteps);

        if($strength < 1){
            $strength = 1;
        }

        return  $strength;
    }


    public function replace($steps = 1){
        if($this->steps < $this->origSteps){
            $this->steps += $steps;
            if($this->steps > $this->origSteps){
                $this->steps = $this->origSteps;
            }
            return true;
        }
        return false;
    }

    public function __get($name)
    {

        $b = Battle::getBattle();
        if ($name !== "strength" && $name !== "attStrength" && $name !== "defStrength" && $name !== "isReduced") {
            return false;
        }
        if($name === "isReduced"){
            return $this->origSteps > $this->steps;
        }
        $strength = $this->getUnmodifiedStrength();

        $strength = $this->getCombatAdjustments($strength);

        return $strength;
    }


    function set($unitName,
                  $unitForceId,
                  $unitHexagon,
                  $unitImage,
                  $strength,
                  $unitMaxMove,
                  $unitStatus,
                  $unitReinforceZone,
                  $unitReinforceTurn,
                  $nationality,
                  $class,
                  $unitDesig,
                  $curSteps,
                  $maxSteps = false 
                    )
    {

        $this->dirty = true;
        $this->name = $unitName;
        $this->forceId = $unitForceId;
        $this->class = $class;

        $this->hexagon = new Hexagon($unitHexagon);
        $this->steps = $curSteps;
        if($maxSteps === false){
            $maxSteps = $curSteps;
        }
        $this->origStrength = $strength;
        $this->origSteps = $maxSteps;

        $battle = Battle::getBattle();
        $mapData = $battle->mapData;

        $mapHex = $mapData->getHex($this->hexagon->getName());
        if ($mapHex) {
            $mapHex->setUnit($this->forceId, $this);
        }
        $this->image = $unitImage;


        $this->maxMove = $unitMaxMove;
        $this->moveAmountUnused = $unitMaxMove;
        $this->status = $unitStatus;
        $this->moveAmountUsed = 0;
        $this->reinforceZone = $unitReinforceZone;
        $this->reinforceTurn = $unitReinforceTurn;
        $this->combatNumber = 0;
        $this->combatIndex = 0;
        $this->combatOdds = "";
        $this->moveCount = 0;
        $this->retreatCountRequired = 0;
        $this->combatResults = NE;
        $this->range = 1;
        $this->nationality = $nationality;
        $this->unitDesig = $unitDesig;
        $this->vp = 0;
    }

    function eliminate(){
    }

    function damageUnit($kill = false)
    {
        $battle = Battle::getBattle();

        if ($this->steps === 1 || $kill) {

            $this->status = STATUS_ELIMINATING;
            $this->exchangeAmount = $this->getUnmodifiedStrength();
            $this->defExchangeAmount = $this->getUnmodifiedStrength();
            return true;
        } else {


            $before = $this->getUnmodifiedStrength();
            $this->steps--;
            $after = $this->getUnmodifiedStrength();
            $this->damage = $before - $after;
            $battle->victory->reduceUnit($this);
            $this->exchangeAmount = 1;
            $this->defExchangeAmount = 1;
        }
        return false;
    }

    function __construct($data = null)
    {
        if ($data) {
            foreach ($data as $k => $v) {
                if ($k == "hexagon") {
                    $this->hexagon = new Hexagon($v);
                    continue;
                }
                $this->$k = $v;
            }
            $this->dirty = false;
        } else {
        }
        $this->forceMarch = true;
    }

    public function fetchData(){
        $mapUnit = new StdClass();
        $mapUnit->parent = $this->hexagon->parent;
        $mapUnit->moveAmountUsed = $this->moveAmountUsed;
        $mapUnit->maxMove = $this->getMaxMove();
        $mapUnit->strength = $this->strength;
        $mapUnit->class = $this->class;
        $mapUnit->id = $this->id;
        $mapUnit->range = $this->range;
        $mapUnit->range = $this->range;
        $mapUnit->status = $this->status;
        $mapUnit->forceId = $this->forceId;
        $mapUnit->supplied = $this->supplied;
        $mapUnit->steps = $this->steps;
        $mapUnit->origSteps = $this->origSteps;
        $mapUnit->hexagon = $this->hexagon->name;
        $mapUnit->image = $this->image;
        $mapUnit->unitDesig = $this->unitDesig;
        $mapUnit->nationality = $this->nationality;
        $mapUnit->name = $this->name;
        return $mapUnit;
    }

    function setStatus($status)
    {
        $battle = Battle::getBattle();
        $success = false;
        $prevStatus = $this->status;
        switch ($status) {
            case STATUS_EXCHANGED:
                if (($this->status == STATUS_CAN_DEFEND_LOSE || $this->status == STATUS_CAN_ATTACK_LOSE || $this->status == STATUS_CAN_EXCHANGE)) {
                    $this->damageUnit();
                    $success = true;
                }
                break;

            case STATUS_REPLACING:
                if ($this->status == STATUS_CAN_REPLACE) {
                    $this->status = $status;
                    $success = true;
                }
                break;

            case STATUS_REPLACED:
                if ($this->status == STATUS_REPLACING) {
                    $this->status = $status;
                    $success = true;
                }
                break;

            case STATUS_CAN_REPLACE:
                if ($this->status == STATUS_REPLACING) {
                    $this->status = $status;
                    $success = true;
                }
                break;

            case STATUS_REINFORCING:
                if ($this->status == STATUS_CAN_REINFORCE) {
                    $this->status = $status;
                    $success = true;
                }
                break;

            case STATUS_DEPLOYING:
                if ($this->status == STATUS_CAN_DEPLOY) {
                    $this->status = $status;
                    $success = true;
                }
                break;

            case STATUS_CAN_REINFORCE:
                if ($this->status == STATUS_REINFORCING) {
                    $this->status = $status;
                    $success = true;
                }
                break;

            case STATUS_CAN_DEPLOY:
                if ($this->status == STATUS_DEPLOYING) {
                    $this->status = $status;
                    $success = true;
                }
                break;

            case STATUS_READY:
            case STATUS_DEFENDING:
            case STATUS_ATTACKING:
                $this->status = $status;
                $id = $this->id;
                if ($status === STATUS_ATTACKING) {
                    if ($battle->force->combatRequired && isset($battle->force->requiredAttacks->$id)) {
                        $battle->force->requiredAttacks->$id = false;
                    }
                }
                if ($status === STATUS_DEFENDING) {
                    if ($battle->force->combatRequired && isset($battle->force->requiredDefenses->$id)) {
                        $battle->force->requiredDefenses->$id = false;
                    }
                }
                if ($status === STATUS_READY) {

                    if ($battle->force->combatRequired && isset($battle->force->requiredAttacks->$id)) {
                        $battle->force->requiredAttacks->$id = true;
                    }
                    if ($battle->force->combatRequired && isset($battle->force->requiredDefenses->$id)) {
                        $battle->force->requiredDefenses->$id = true;
                    }
                }
                break;

            case STATUS_MOVING:
                if (($this->status == STATUS_READY || $this->status == STATUS_REINFORCING)
                ) {
                    $this->status = $status;
                    $this->moveCount = 0;
                    $this->moveAmountUsed = 0;
                    $this->moveAmountUnused = $this->getMaxMove();
                    $success = true;
                }
                break;

            case STATUS_STOPPED:
                if ($this->status == STATUS_MOVING || $this->status == STATUS_DEPLOYING) {
                    $this->status = $status;
                    $this->moveAmountUnused = $this->getMaxMove() - $this->moveAmountUsed;
                    $this->moveAmountUsed = $this->getMaxMove();

                    $success = true;
                }
                if ($this->status == STATUS_ADVANCING) {
                    $this->status = STATUS_ADVANCED;
//                    $this->moveAmountUsed = $$this->maxMove;
                    $success = true;
                }
                if ($this->status == STATUS_RETREATING) {
                    $this->status = STATUS_RETREATED;
//                    $this->moveAmountUsed = $$this->maxMove;
                    $success = true;
                }
                break;

            case STATUS_EXITED:
                if ($this->status == STATUS_MOVING) {
                    $this->status = $status;
                    $success = true;
                }
                break;

            case STATUS_RETREATING:
                if ($this->status == STATUS_CAN_RETREAT) {
                    $this->status = $status;
                    $this->moveCount = 0;
                    $this->moveAmountUsed = 0;
                    $success = true;
                }
                break;

            case STATUS_ADVANCING:
                if ($this->status == STATUS_CAN_ADVANCE) {
                    $this->status = $status;
                    $this->moveCount = 0;
                    $this->moveAmountUsed = 0;
                    $success = true;
                }
                break;

            case STATUS_ADVANCED:
                if ($this->status == STATUS_ADVANCING) {
                    $this->status = $status;
                    $success = true;
                }
                break;

            default:
                break;
        }
        $this->dirty = true;
        return $success;
    }

    public function getRange(){
        return $this->range;
    }

    /* 999999999 */

}