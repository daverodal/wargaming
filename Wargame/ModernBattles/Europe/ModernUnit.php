<?php

namespace Wargame\ModernBattles\Europe;

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
class ModernUnit extends BaseUnit implements \JsonSerializable
{

    public $origStrength;
    public $defStrength;
    public $fpf;
    public $movesAllowed = 1;

    public function recover(){

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

        $strength = $this->origStrength;

        $strength = $this->getCombatAdjustments($strength);

        return $strength;
    }

    public function setHexagon($hexNum){
        $this->hexagon = new Hexagon($hexNum);

        $battle = Battle::getBattle();
        $mapData = $battle->mapData;

        $mapHex = $mapData->getHex($this->hexagon->getName());
        if ($mapHex) {
            $mapHex->setUnit($this->forceId, $this);
        }
    }

    function set($unitName, $unitForceId, $unitHexagon, $unitImage, $unitStrength, $defStrength,  $unitMaxMove, $unitStatus, $unitReinforceZone, $unitReinforceTurn, $range, $nationality = "neutral", $class = "", $unitDesig = "", $fpf = 0)
    {
        $this->dirty = true;
        $this->name = $unitName;
        $this->forceId = $unitForceId;
        $this->class = $class;
        if($this->class === 'air'){
            $this->movesAllowed = 3;
        }
        $this->hexagon = new Hexagon($unitHexagon);

        $battle = Battle::getBattle();
        $mapData = $battle->mapData;

        $mapHex = $mapData->getHex($this->hexagon->getName());
        if ($mapHex) {
            $mapHex->setUnit($this->forceId, $this);
        }
        $this->image = $unitImage;
        $this->maxMove = $unitMaxMove;
        $this->moveAmountUnused = $unitMaxMove;
        $this->origStrength = $unitStrength;
        $this->status = $unitStatus;
        $this->defStrength = $defStrength;
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
        $this->fpf = $fpf;
    }

    function damageUnit($kill = false)
    {
        $battle = Battle::getBattle();

        $this->status = STATUS_ELIMINATING;
        $this->exchangeAmount = $this->getUnmodifiedStrength();
        $this->defExchangeAmount = $this->getUnmodifiedDefStrength();
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
        $this->forceMarch = true;
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
        $mapUnit->status = $this->status;
        $mapUnit->forceId = $this->forceId;
        $mapUnit->hexagon = $this->hexagon->name;
        $mapUnit->unitDesig = $this->unitDesig;
        $mapUnit->name = $this->name;
        $mapUnit->nationality = $this->nationality;
        $mapUnit->image = $this->image;
        $mapUnit->defStrength = $this->defStrength;
        $mapUnit->range = $this->range;
        $mapUnit->class = $this->class;
        $mapUnit->fpf = $this->fpf;
        $mapUnit->movesAllowed = $this->movesAllowed;
        return $mapUnit;
    }




    public function postSet()
    {

    }

}
