<?php

namespace Wargame\TMCW\NorthVsSouth;

use \Wargame\Hexagon;
use \stdClass;
use \Wargame\Battle;
use \Wargame\BaseUnit;

/**
 * Copyright 2015 David Rodal
 * User: David Markarian Rodal
 * Date: 12/21/15
 * Time: 2:09 PM
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
class SimpleUnit extends BaseUnit implements \JsonSerializable
{

    public $origStrength;
    public $supplied = true;
    public $saveMaxMove = false;
    public $untriedStrength;
    public $tried = false;

    public function railMove(bool $mode)
    {
        $b = Battle::getBattle();
        $turn = $b->gameRules->turn;
        if($this->class === "railhead" && $this->forceId === NorthVsSouth::NORTHERN_FORCE){
            $this->forceMarch = true;
        }else{
            if($turn > 1 && $turn === $this->reinforceTurn && $b->gameRules->phase === RED_MOVE_PHASE){
                if($mode === true){
                    $this->saveMaxMove = $this->maxMove;
                    $this->maxMove = 30;
                    $this->forceMarch = true;
                    $this->saveClass = $this->class;
                    $this->class= "railhead";
                    return;
                }else{
                    $this->recover();
                }
            }
            $this->forceMarch = false;
        }

    }

    public function recover(){
        if($this->saveMaxMove){
            $this->maxMove = $this->saveMaxMove;
            $this->class = $this->saveClass;
            $this->saveMaxMove = $this->saveClass = false;
            $this->forceMarch = false;
        }
    }

    public function getUnmodifiedStrength()
    {
        return $this->origStrength;
    }


    public function getUnmodifiedDefStrength()
    {
        return $this->origStrength;
    }

    public function __get($name)
    {
        if ($name !== "strength" && $name !== "defStrength" && $name !== "attStrength") {
            return false;
        }
        $strength = $this->untriedStrength;

        if($this->tried){
            $strength = $this->origStrength;
        }

        $strength = $this->getCombatAdjustments($strength);

        return $strength;
    }


    function set($unitName, $unitForceId, $unitHexagon, $unitImage, $unitStrength, $untriedStrength,  $unitMaxMove, $unitStatus, $unitReinforceZone, $unitReinforceTurn, $range, $nationality = "neutral", $class = "", $unitDesig = "")
    {
        $this->dirty = true;
        $this->tried = false;
        $this->name = $unitName;
        $this->forceId = $unitForceId;
        $this->class = $class;
        $this->hexagon = new Hexagon($unitHexagon);

        $battle = Battle::getBattle();
        $mapData = $battle->mapData;

        $mapHex = $mapData->getHex($this->hexagon->getName());
        if ($mapHex) {
            $mapHex->setUnit($this->forceId, $this);
        }
        $this->image = $unitImage;


        if($this->class === "railhead"){
            $this->forceMarch = true;
            $this->noZoc = true;
        }else{
            $this->forceMarch = false;
            $this->noZoc = false;
        }
        $this->maxMove = $unitMaxMove;
        $this->moveAmountUnused = $unitMaxMove;
        $this->origStrength = $unitStrength;
        $this->untriedStrength = $untriedStrength;
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
        $this->range = $range;
        $this->nationality = $nationality;
        $this->unitDesig = $unitDesig;

    }

    function damageUnit($kill = false)
    {
        $battle = Battle::getBattle();

        $this->status = STATUS_ELIMINATING;
        $this->exchangeAmount = $this->getUnmodifiedStrength();
        $this->defExchangeAmount = $this->getUnmodifiedDefStrength();
        $this->tried = false;
        return true;
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
    }

    public function getRange()
    {
        return $this->range;
    }

    public function fetchData()
    {
        $mapUnit = new stdClass();
        $mapUnit->parent = $this->hexagon->parent;
        $mapUnit->moveAmountUsed = $this->moveAmountUsed;
        $mapUnit->maxMove = $this->getMaxMove();
        $mapUnit->strength = $this->strength;
        $mapUnit->supplied = $this->supplied;
        $mapUnit->status = $this->status;
        $mapUnit->forceId = $this->forceId;
        $mapUnit->hexagon = $this->hexagon->name;
        $mapUnit->unitDesig = $this->unitDesig;
        $mapUnit->name = $this->name;
        $mapUnit->tried = $this->tried;
        return $mapUnit;
    }




    public function postSet()
    {
        if ($this->class === "supply") {
            $this->supplyRadius = 6;
        }
    }

}
