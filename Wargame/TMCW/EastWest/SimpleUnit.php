<?php

namespace Wargame\TMCW\EastWest;

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
    public $unitDefStrength;
    public $supplied = true;
    public $hadAnySupplies = true;

    public $moveByRail = false;

    public function railMove(bool $mode)
    {

        $b = Battle::getBattle();
        if ($mode == true) {
            $mapHex = $b->mapData->getHex($this->hexagon->getName());
            /* units that start in zoc can't rail move */
            if($b->force->mapHexIsZOC($mapHex)){
                return;
            }
            if (!($this->class === "supply" || $this->class === "art") && ($b->gameRules->phase === BLUE_MOVE_PHASE || $b->gameRules->phase == RED_MOVE_PHASE)) {
                $this->moveByRail = true;
            }
            if (($this->class === "supply" || $this->class === "art" ) && ($b->gameRules->phase === BLUE_MECH_PHASE || $b->gameRules->phase == RED_MECH_PHASE)) {
                $this->moveByRail = true;

            }
            return;
        }
        $b->moveRules->noZoc = false;
        $this->moveByRail = false;
    }

    public function getMaxMove()
    {
        $b = Battle::getBattle();
        if ($this->moveByRail === true) {
            if (!($this->class === "supply" || $this->class === "art") && ($b->gameRules->phase === BLUE_MOVE_PHASE || $b->gameRules->phase == RED_MOVE_PHASE)) {

                return 12;
            }
            if (($this->class === "supply" || $this->class === "art") && ($b->gameRules->phase === BLUE_MECH_PHASE || $b->gameRules->phase == RED_MECH_PHASE)) {
                return 12;
            }
        }
        return parent::getMaxMove();
    }

    public function getUnmodifiedStrength()
    {
        return $this->origStrength;
    }


    public function getUnmodifiedDefStrength()
    {
        return $this->unitDefStrength;
    }

    public function startSupplyCheck(){
        $this->hadAnySupplies = $this->supplied;
    }

    public function gotSupplied(){
        $this->hadAnySupplies = true;
    }

    public function killUnsupplied(){
        if($this->hadAnySupplies === false){
            $this->damageUnit(true);
            $b = Battle::getBattle();
            $b->force->eliminateUnit($this->id);
        }

    }
    public function __get($name)
    {
        if ($name !== "strength" && $name !== "defStrength" && $name !== "attStrength") {
            return false;
        }
        $strength = $this->origStrength;

        if ($name === "defStrength") {
            $strength = $this->unitDefStrength;
        }

        $strength = $this->getCombatAdjustments($strength);

        return $strength;
    }


    function set($unitName, $unitForceId, $unitHexagon, $unitImage, $unitStrength, $unitDefStrength, $unitMaxMove, $unitStatus, $unitReinforceZone, $unitReinforceTurn, $range, $nationality = "neutral", $class = "", $unitDesig = "")
    {
        $this->dirty = true;
        $this->name = $unitName;
        $this->forceId = $unitForceId;
        $this->class = $class;
        $this->hexagon = new Hexagon($unitHexagon);

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
        $this->origStrength = $unitStrength;
        $this->unitDefStrength = $unitDefStrength;
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
        $this->forceMarch = false;
        $this->unitDesig = $unitDesig;
        if($class == 'supply' || $class == 'art'){
            $this->noZoc = true;
        }

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
//                    $this->hexagon->parent = $data->parent;
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
        $mapUnit->defStrength = $this->unitDefStrength;
        $mapUnit->status = $this->status;
        $mapUnit->forceId = $this->forceId;
        $mapUnit->facing = 0;
        $mapUnit->hexagon = $this->hexagon->name;
        $mapUnit->unitDesig = $this->unitDesig;
        $mapUnit->supplyUsed = $this->supplyUsed;
        if ($this->supplyRadius !== false) {
            $mapUnit->supplyRadius = $this->supplyRadius;
        }
        return $mapUnit;
    }


    public $supplyUsed = false;
    public $supplyRadius = false;

    public $canTransport = false;

    public $carries = false;
    public $carriedBy = false;


    public function postSet()
    {
        if ($this->class === "supply") {
            $this->supplyRadius = 6;
        }
    }

}
