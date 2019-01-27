<?php
namespace Wargame\TMCW\Manchuria1976;
use Wargame\Battle;
use Wargame\Hexagon;
/**
 *
 * Copyright 2012-2015 David Rodal
 * User: David Markarian Rodal
 * Date: 3/8/15
 * Time: 5:48 PM
 *
 *  This program is free software; you can redistribute it
 *  and/or modify it under the terms of the GNU General Public License
 *  as published by the Free Software Foundation;
 *  either version 2 of the License, or (at your option) any later version
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
/**
 * Created by JetBrains PhpStorm.
 * User: markarianr
 * Date: 5/7/13
 * Time: 7:06 PM
 * To change this template use File | Settings | File Templates.
 */

class victoryCore extends \Wargame\TMCW\victoryCore
{
    public $sovietGoal;
    public $supplyUnits;
    public $chineseCasualities;

    function __construct($data)
    {
        parent::__construct($data);
        if ($data) {
            $this->sovietGoal = $data->victory->sovietGoal;
            $this->supplyUnits = $data->victory->supplyUnits;
            $this->chineseCasualities = $data->victory->chineseCasualities;
        } else {
            $this->sovietGoal = [];
            $this->supplyUnits = [];
            $this->chineseCasualities = 0;
            $this->victoryPoints[Manchuria1976::PRC_FORCE] = 100;
        }
    }

    public function save()
    {
        $ret = parent::save();
        $ret->sovietGoal = $this->sovietGoal;
        $ret->supplyUnits = $this->supplyUnits;
        $ret->chineseCasualities = $this->chineseCasualities;
        return $ret;
    }

    public function isDeterminedAble($args){
        list($cd, $combat) = $args;
        $b = Battle::getBattle();
        $prc = $b->force->attackingForceId === Manchuria1976::PRC_FORCE;
        if($prc){
            return true;
        }
        return false;
    }

    public function specialHexChange($args)
    {
        $battle = Battle::getBattle();

        list($mapHexName, $forceId) = $args;


        if(in_array($mapHexName, $battle->specialHexA)) {


            if ($forceId == Manchuria1976::SOVIET_FORCE) {
                $this->victoryPoints[Manchuria1976::SOVIET_FORCE] += 10;
                $this->victoryPoints[Manchuria1976::PRC_FORCE] -= 10;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='sovietVictoryPoints'>+10 Sovietm -10 PRC vp</span>";
            }
            if ($forceId == Manchuria1976::PRC_FORCE) {
                $this->victoryPoints[Manchuria1976::SOVIET_FORCE] -= 10;
                $this->victoryPoints[Manchuria1976::PRC_FORCE] += 10;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='prcVictoryPoints'>-10 Soviet +10 PRC vp</span>";
            }
        }
        if(in_array($mapHexName, $battle->specialHexB)){

            if ($forceId == Manchuria1976::SOVIET_FORCE) {
                $this->victoryPoints[Manchuria1976::PRC_FORCE] -= 30;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='sovietVictoryPoints'>-30 PRC vp</span>";
            }
            if ($forceId == Manchuria1976::PRC_FORCE) {
                $this->victoryPoints[Manchuria1976::PRC_FORCE] += 30;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='prcVictoryPoints'>+30 PRC vp</span>";
            }

        }


        if(in_array($mapHexName, $battle->specialHexC)){

            if ($forceId == Manchuria1976::SOVIET_FORCE) {
                $this->victoryPoints[Manchuria1976::PRC_FORCE] -= 15;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='sovietVictoryPoints'>-15 PRC vp</span>";
            }
            if ($forceId == Manchuria1976::PRC_FORCE) {
                $this->victoryPoints[Manchuria1976::PRC_FORCE] += 15;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='prcVictoryPoints'>+15 PRC vp</span>";
            }

        }
    }

    public function postEliminated($args){
        list($unit) = $args;
        if($unit->class === "gorilla"){
            $unit->hexagon->parent = "undeadpile";
        }
    }

    public function resurrectMilitia($city){
        $b = Battle::getBattle();
        foreach($b->force->units as $k => $unit){
            if($unit->status === STATUS_ELIMINATED){
                if($unit->forceId === Manchuria1976::PRC_FORCE){
                    if($unit->class === 'militia'){
                        $unit->status = STATUS_READY;
                        $hexagon = new Hexagon($city);
                        $unit->updateMoveStatus($hexagon, 0);
                    }
                }
            }
        }

    }
    public function checkCityRevolt(){
        $b = Battle::getBattle();
        $m = $b->mapData;
        $cities = $b->specialHexA;
        foreach($cities as $city){
            if($b->mapData->getSpecialHex($city) !== Manchuria1976::PRC_FORCE){
                $mapHex = $b->mapData->getHex($city);
                if (!$mapHex->isOccupied(Manchuria1976::SOVIET_FORCE)) {
                    $victory = $b->victory;
                    $m->specialHexesChanges->$city = true;
                    $victory->specialHexChange($city, Manchuria1976::PRC_FORCE);
                    $m->alterSpecialHex($city, Manchuria1976::PRC_FORCE);
                    $this->resurrectMilitia($city);
                }
            }
        }
    }
    public function postReinforceZones($args)
    {
        $battle = Battle::getBattle();

        list($zones, $unit) = $args;
        if ($unit->nationality == "prc") {
            $battle = Battle::getBattle();
            $mapData = $battle->mapData;

            if ($unit->class == "gorilla") {

                $newZones = [];
                /* @var Force $force */
                $force = $battle->force;
                foreach ($zones as $zone) {
                    $mapHex = $mapData->getHex($zone->hexagon->name);
                    if ($force->mapHexIsZOC($mapHex)) {
                        continue;
                    }
                    $newZones[] = $zone;
                }
                return array($newZones);
            }

            /* @var MapData $mapData */
            $mapData = $battle->mapData;
            $specialHexes = $battle->specialHexA;

            $zones = [];
            foreach ($specialHexes as $specialHex) {
                if ($mapData->getSpecialHex($specialHex) == Manchuria1976::PRC_FORCE) {
                    $zones[] = new \Wargame\ReinforceZone($specialHex, $specialHex);
                }
            }
        }
        return array($zones);
    }

    public function reduceUnit($args)
    {
        $unit = $args[0];
        if ($unit->strength == $unit->maxStrength) {
            if ($unit->status == STATUS_ELIMINATING || $unit->status == STATUS_RETREATING) {
                $vp = $unit->maxStrength;
            } else {
                $vp = $unit->maxStrength - $unit->minStrength;
            }
        } else {
            $vp = $unit->minStrength;
        }
        if ($unit->forceId == Manchuria1976::PRC_FORCE) {
            $victorId = Manchuria1976::SOVIET_FORCE;
            $hex = $unit->hexagon;
            $this->chineseCasualities += $vp;
            $battle = Battle::getBattle();
            $battle->mapData->specialHexesVictory->{$hex->name} = "<span class='sovietVictoryPoints'>+$vp Casualities</span>";
        } else {
            $victorId = Manchuria1976::PRC_FORCE;
            $this->victoryPoints[$victorId] += $vp * 1.5;
            $hex = $unit->hexagon;
            $battle = Battle::getBattle();
            $battle->mapData->specialHexesVictory->{$hex->name} = "<span class='prcVictoryPoints'>+$vp vp</span>";
        }
    }

    public function incrementTurn()
    {

    }

    public function phaseChange()
    {

        /* @var $battle MartianCivilWar */
        $battle = Battle::getBattle();
        /* @var $gameRules GameRules */
        $gameRules = $battle->gameRules;
        $forceId = $gameRules->attackingForceId;
        $turn = $gameRules->turn;

        $this->checkCityRevolt();
        if ($gameRules->phase == RED_COMBAT_PHASE || $gameRules->phase == BLUE_COMBAT_PHASE) {
            $gameRules->flashMessages[] = "@hide deployWrapper";
        } else {
            $gameRules->flashMessages[] = "@hide crt";

            /* Restore all un-supplied strengths */
            $force = $battle->force;
            foreach ($this->combatCache as $id => $strength) {
                $unit = $force->getUnit($id);
                $unit->removeAdjustment('supply');
//                $unit->strength = $strength;
                unset($this->combatCache->$id);
            }
        }
        if ($gameRules->phase == BLUE_REPLACEMENT_PHASE || $gameRules->phase == RED_REPLACEMENT_PHASE) {
            $gameRules->flashMessages[] = "@show deadpile";
            $forceId = $gameRules->attackingForceId;
        }
        if ($gameRules->phase == BLUE_MOVE_PHASE || $gameRules->phase == RED_MOVE_PHASE) {
            $gameRules->flashMessages[] = "@hide deadpile";
            if (isset($battle->force->reinforceTurns->$turn->$forceId)) {
                $gameRules->flashMessages[] = "@show deployWrapper";
                $gameRules->flashMessages[] = "Reinforcements have been moved to the Deploy/Staging Area";
            }
        }
    }

    public function preRecoverUnits(){

        $b = Battle::getBattle();

        if (!empty($b->scenario->supply) === true) {
            $bias = array(2 => true, 3 => true);
            $goal = $b->moveRules->calcRoadSupply(Manchuria1976::SOVIET_FORCE, 3920, $bias);
            $goal = array_merge($goal, $b->moveRules->calcRoadSupply(Manchuria1976::SOVIET_FORCE, 1932, $bias));
            $goal = array_merge($goal, $b->moveRules->calcRoadSupply(Manchuria1976::SOVIET_FORCE, 3233, $bias));
            $goal = array_merge($goal, array(3910, 3911, 3912, 3913, 3914, 3915, 3916, 3917, 3918, 3919));
            $this->sovietGoal = $goal;
            $units = $b->force->units;
            $supplyUnits = [];
            foreach($units as $unit){
                if($unit->class === 'supply'){
                    $supplyUnits[] = $unit->hexagon->name;
                }
            }
            $this->supplyUnits = $supplyUnits;
        }

    }
    public function postRecoverUnit($args)
    {
        /* @var unit $unit */
        $unit = $args[0];
        $supplyLen = 12;

        $b = Battle::getBattle();
        $id = $unit->id;
        if ($unit->forceId != $b->gameRules->attackingForceId) {
//            return;
        }
        $goal = $this->sovietGoal;
        if (!empty($b->scenario->supply) === true) {
            if ($unit->forceId == Manchuria1976::PRC_FORCE) {
                return; /* in supply in china, should verify we ARE in china, but..... */
            }
            if ($unit->forceId == Manchuria1976::SOVIET_FORCE) {
                $bias = array(2 => true, 3 => true);
            }

            if ($b->gameRules->mode == REPLACING_MODE) {
                if ($unit->status == STATUS_CAN_UPGRADE) {
                    $unit->supplied = $this->calcSupply($unit->id, $goal, $bias, $supplyLen);
                    /* unsupplied units cannot receive replacements */
                    if (!$unit->supplied) {
                        $unit->status = STATUS_STOPPED;
                    }
                }
                return;
            }
            if ($b->gameRules->mode == MOVING_MODE) {
                if ($unit->status == STATUS_READY || $unit->status == STATUS_UNAVAIL_THIS_PHASE) {
                    $unit->supplied = $this->calcSupply($unit->id, $goal, $bias, $supplyLen);
                } else {
                    return;
                }
                if (!$unit->supplied) {
                    $unit->addAdjustment("supply", "halfMovement");
                }
                if ($unit->supplied) {
                    $unit->removeAdjustment("supply");
                }
            }
            if ($b->gameRules->mode == COMBAT_SETUP_MODE) {
                if ($unit->status == STATUS_READY || $unit->status == STATUS_DEFENDING || $unit->status == STATUS_UNAVAIL_THIS_PHASE) {
                    $unit->supplied = $this->calcSupply($unit->id, $goal, $bias, $supplyLen);
                } else {
                    return;
                }
                if ($unit->forceId == $b->gameRules->attackingForceId && !$unit->supplied && !isset($this->combatCache->$id)) {
                    $this->combatCache->$id = true;
                    $unit->addAdjustment('supply','floorHalf');
                }
                if ($unit->supplied && isset($this->combatCache->$id)) {
                    $unit->removeAdjustment('supply');
                    unset($this->combatCache->$id);
                }
                if ($unit->supplied && isset($this->movementCache->$id)) {
                    $unit->maxMove = $this->movementCache->$id;
                    unset($this->movementCache->$id);
                }
            }
        }
    }

    public function calcSupply($id, $goal, $bias, $supplyLen){
        $b = Battle::getBattle();
        $supplied = $b->moveRules->calcSupply($id, $goal, $bias, $supplyLen);
        if (!$supplied) {
            $supplied = $b->moveRules->calcSupply($id, $this->supplyUnits, $bias, 5);
            if(!$supplied){
                return false;
            }
            /* TODO: make this not cry  (call a method) */
        }
        return true;
    }
    public function preCombatResults($args)
    {
        return $args;
        list($defenderId, $attackers, $combatResults, $dieRoll) = $args;
        $battle = Battle::getBattle();
        /* @var mapData $mapData */
        $mapData = $battle->mapData;
        $unit = $battle->force->getUnit($defenderId);
        $defendingHex = $unit->hexagon->name;
        if ($defendingHex == 407 || $defendingHex == 2415 || $defendingHex == 2414 || $defendingHex == 2515) {
            /* Cunieform */
            if ($unit->forceId == RED_FORCE) {
                if ($combatResults == DR2) {
                    $combatResults = NE;
                }
                if ($combatResults == DRL2) {
                    $combatResults = DL;
                }
            }
        }
        return array($defenderId, $attackers, $combatResults, $dieRoll);
    }

    public function preStartMovingUnit($arg)
    {
        $unit = $arg[0];
        $battle = Battle::getBattle();
        if (!empty($battle->scenario->supply) === true && $unit->isOnMap()) {
            if ($unit->class != 'mech') {
                $battle->moveRules->enterZoc = "stop";
                $battle->moveRules->exitZoc = 0;
                $battle->moveRules->noZocZoc = true;
                if ($battle->terrain->terrainIsHex($unit->hexagon->name, "mountain")) {
                    $battle->moveRules->noZocZoc = false;
                }
            } else {
                $battle->moveRules->enterZoc = 2;
                $battle->moveRules->exitZoc = 1;
                $battle->moveRules->noZocZoc = false;
            }
            if($unit->railMode){
                $battle->moveRules->oneHex = false;
            }else{
                $battle->moveRules->oneHex = true;
            }
        }
    }

    public function playerTurnChange($arg)
    {
        $attackingId = $arg[0];
        $battle = Battle::getBattle();
        $mapData = $battle->mapData;
        $gameRules = $battle->gameRules;
        $theUnits = $battle->force->units;


        if ($gameRules->phase == BLUE_MECH_PHASE || $gameRules->phase == RED_MECH_PHASE) {
            $gameRules->flashMessages[] = "@hide crt";
        }
        if ($attackingId == Manchuria1976::SOVIET_FORCE) {
            $gameRules->flashMessages[] = "Soviet Player Turn";
            $gameRules->replacementsAvail = 1;
        }
        if ($attackingId == Manchuria1976::PRC_FORCE) {
            $gameRules->flashMessages[] = "PRC Player Turn";
            $gameRules->replacementsAvail = 5;
        }

        $gorillaCnt = 0;
        foreach($theUnits as $unit) {
            if(($unit->isOnMap() || $unit->hexagon->parent === 'deployBox') && $unit->class === 'gorilla'){
                $gorillaCnt++;
            }
        }
        foreach ($theUnits as $id => $unit) {

            if ($unit->forceId === $attackingId && $unit->status == STATUS_CAN_REINFORCE && $unit->reinforceTurn <= $battle->gameRules->turn && strpos($unit->hexagon->parent, "gameTurn") === 0) {
                if($gorillaCnt < 3){
                    $theUnits[$id]->hexagon->parent = "deployBox";
                    $gorillaCnt++;
                }else{
                    $theUnits[$id]->hexagon->parent = "notUsed";
                    $theUnits[$id]->status = STATUS_ELIMINATED;
                }
            }
        }
    }
}