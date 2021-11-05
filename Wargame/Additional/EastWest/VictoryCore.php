<?php
namespace Wargame\Additional\EastWest;
use \Wargame\Battle;
use \stdClass;
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

class VictoryCore extends \Wargame\TMCW\victoryCore
{

    public $cityValues;

    public $sovietGoal;
    public $germanGoal;
    public $unsuppliedDefenderHalved = false;

    function unitSupplyEffects($unit, $goal, $bias, $supplyLen){

        $b = Battle::getBattle();
        $id = $unit->id;

        if ($unit->isOnMap() && $unit->status == STATUS_READY || $unit->status == STATUS_UNAVAIL_THIS_PHASE) {
            $unit->supplied = $b->moveRules->calcSupply($unit->id, $goal, $bias, $supplyLen);
        } else {
            return;
        }
        if (!$unit->supplied) {
            $unit->addAdjustment('movement','zeroMovement');
        }
        if ($unit->supplied) {
            $unit->gotSupplied();
            $unit->removeAdjustment('movement');
        }
        $unit->removeAdjustment('supply');
    }

    function unitCombatSupplyEffects($unit, $goal, $bias, $supplyLen){

        $b = Battle::getBattle();

        if ($unit->isOnMap()) {
            $unit->supplied = $b->moveRules->calcSupply($unit->id, $goal, $bias, $supplyLen);
        } else {
            return;
        }

        $unit->removeAdjustment('movement');

        if ((!empty($this->unsuppliedDefenderHalved) || $unit->forceId == $b->gameRules->attackingForceId) && !$unit->supplied) {
            $unit->addAdjustment('supply','zero');
            $b->combatRules->recalcCombat($unit->id);
        }else{
            $unit->removeAdjustment('supply');
            $b->combatRules->recalcCombat($unit->id);
        }

    }

    function __construct($data)
    {
        parent::__construct($data);
        $this->cityValues = [706 => 16, 909 => 2, 712 => 1, 1803 => 6, 1705 => 1, 1907=> 2, 1409 => 5, 2010 => 3,
            1811 => 2, 1613 => 2, 1412 => 4, 1213 =>3, 1215 => 3, 1016 => 8,
            717 => 2, 518 => 3, 2405 => 2 , 2809 => 8, 2907 => 4, 2214 => 12, 2218 => 6, 2121 => 3, 3717 => 3, 1419 => 2, 1319 => 2, 3117 => 3];

        if ($data) {
            $this->germanGoal = $data->victory->germanGoal;
            $this->sovietGoal = $data->victory->sovietGoal;
        } else {
            /* German goal is west Edge */
            for($i = 1; $i <= 20;$i++){
                $germanGoal[] = 100 + $i;
            }
            /* Soviet goal is west Edge */
            for($i = 1; $i <= 20    ;$i++){
                $sovietGoal[] = 3000 + $i;
            }
            $this->germanGoal = $germanGoal;
            $this->sovietGoal = $sovietGoal;
            $this->victoryPoints = [0, 26, 77, 0, 0];

        }
    }

    public function save()
    {
        $ret = parent::save();
        $ret->germanGoal = $this->germanGoal;
        $ret->sovietGoal = $this->sovietGoal;
        $ret->cityValues = $this->cityValues;
        return $ret;
    }
    public function setVictoryPoints($victoryPoints){
        $this->victoryPoints = $victoryPoints[0];

    }
    public function setSupplyLen($supplyLen)
    {
        $this->supplyLen = $supplyLen[0];
    }

    public function specialHexChange($args)
    {
        $battle = Battle::getBattle();
        list($mapHexName, $forceId) = $args;

            $hexNum = preg_replace("/^0/", '', $mapHexName);
            if ($forceId == EastWest::SOVIET_FORCE) {
                $vp = $this->cityValues[$hexNum];
                $this->victoryPoints[EastWest::SOVIET_FORCE] += $vp;
                $this->victoryPoints[EastWest::GERMAN_FORCE] -= $vp;
                $battle->mapData->specialHexesVictory->{$mapHexName} = "<span class='soviet'>+$vp</span>";
            }
            if ($forceId == EastWest::GERMAN_FORCE) {
                $vp = $this->cityValues[$hexNum];
                $this->victoryPoints[EastWest::GERMAN_FORCE] += $vp;
                $this->victoryPoints[EastWest::SOVIET_FORCE] -= $vp;
                $battle->mapData->specialHexesVictory->{$mapHexName} = "<span class='german'>+$vp</span>";

            }

    }
//
//    public function postReinforceZones($args)
//    {
//        list($zones, $unit) = $args;
//
//        $forceId = $unit->forceId;
//
//        $reinforceZones = [];
//        foreach($zones as $zone){
//            $reinforceZones[] = new \Wargame\ReinforceZone($zone, $zone);
//        }
//        $battle = Battle::getBattle();
//
//        return array($reinforceZones);
//    }

    public function reduceUnit($args)
    {
        $unit = $args[0];
        if($unit->supplyUsed){
            return;
        }
        $vp = $unit->damage;
        if ($unit->forceId == 1) {
            $victorId = 2;
            $vp = 2;
            if($unit->defStrength === 7){
                $vp = 3;
            }
            if($unit->defStrength === 8){
                $vp = 9;
            }
            $this->victoryPoints[$victorId] += $vp;
            $hex = $unit->hexagon;
            $battle = Battle::getBattle();
            if(empty($battle->mapData->specialHexesVictory->{$hex->name})){
                $battle->mapData->specialHexesVictory->{$hex->name} = '';
            }
            $battle->mapData->specialHexesVictory->{$hex->name} .= "<span class='sovietVictoryPoints'>+$vp</span>";
        } else {
            $vp = 1;
            if($unit->defStrength === 4){
                $vp = 1;
            }
            if($unit->defStrength === 2){
                $vp = 2;
            }
            if($unit->defStrength === 1 && $unit->attStrength === 2){
                $vp = 3;
            }
            $victorId = 1;
            $hex  = $unit->hexagon;
            $battle = Battle::getBattle();
            if(empty($battle->mapData->specialHexesVictory->{$hex->name})){
                $battle->mapData->specialHexesVictory->{$hex->name} = '';
            }
            $battle->mapData->specialHexesVictory->{$hex->name} .= "<span class='germanVictoryPoints'>+$vp</span>";
            $this->victoryPoints[$victorId] += $vp;
        }
    }

    public function checkSurrounded(){
        $battle = Battle::getBattle();

        $gameRules = $battle->gameRules;
        $this->victoryPoints[3] = 0;
        if(!$this->gameOver){
            $battle = Battle::getBattle();

            $units = $battle->force->units;
            foreach($units as $unit){
                if($unit->forceId == EastWest::SOVIET_FORCE){
                    if($unit->hexagon->parent == "gameImages"){
                        if($unit->supplied === false){
                            $this->victoryPoints[3] += $unit->getUnmodifiedStrength();
                        }
                    }
                }
            }

        }
    }
    protected function checkVictory($attackingId, $battle){

        $this->checkSurrounded();
        return false;
    }

    public function incrementTurn()
    {


    }

    public function gameEnded()
    {
        $battle = Battle::getBattle();
//        $kiev = $battle->specialHexC[0];
//        if ($battle->mapData->getSpecialHex($kiev) === EastWest::SOVIET_FORCE) {
//            $battle->gameRules->flashMessages[] = "Soviet Player Wins";
//        }else{
//            $battle->gameRules->flashMessages[] = "German Player Wins";
//        }
        $battle->gameRules->flashMessages[] = "Nobody Wins";

        $this->gameOver = true;
        return true;
    }

    public function phaseChange()
    {

        /* @var $battle MartianCivilWar */
        $battle = Battle::getBattle();
        /* @var $gameRules GameRules */
        $gameRules = $battle->gameRules;
        $forceId = $gameRules->attackingForceId;
        $turn = $gameRules->turn;
        $force = $battle->force;
        $theUnits = $battle->force->units;


        foreach ($theUnits as $id => $unit) {
            $unit->railMove(false);
        }
        if ($gameRules->phase == RED_COMBAT_PHASE || $gameRules->phase == BLUE_COMBAT_PHASE) {
            $gameRules->flashMessages[] = "@hide deployWrapper";
        } else {
            $gameRules->flashMessages[] = "@hide crt";

            /* Restore all un-supplied strengths */
            $force = $battle->force;
            $this->restoreAllCombatEffects($force);
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

            foreach ($theUnits as $id => $unit) {
                $unit->railMove(false);
                if($gameRules->turn >= 5 && $gameRules->turn <= 8){
                    if($unit->forceId === EastWest::GERMAN_FORCE){
                        if($unit->class === 'mech'){
                            $unit->addAdjustment('weather', '5Movement');
                        }

                        if($unit->class === 'inf'){
                            $unit->addAdjustment('weather', '2Movement');
                        }
                        if($unit->class === 'art'){
                            $unit->addAdjustment('weather', 'half');
                        }

                    }
                }else{
                    $unit->removeAdjustment('weather');

                }

                if ($unit->status == STATUS_CAN_REINFORCE && $unit->reinforceTurn <= $battle->gameRules->turn && $unit->hexagon->parent != "deployBox") {
                    $theUnits[$id]->hexagon->parent = "deployBox";
                }
            }
        }
    }

    public function preStartMovingUnit($arg)
    {
        $unit = $arg[0];
        $b = $battle = Battle::getBattle();
        /* mech and art and supply pay 2 */
        $battle->moveRules->noZocZocOneHex = false;
        $battle->moveRules->noZocZoc = false;

        if ($unit->class == 'inf') {
            $battle->moveRules->enterZoc = 1;
            $battle->moveRules->exitZoc = 1;

        } else {
            $battle->moveRules->enterZoc = 2;
            $battle->moveRules->exitZoc = 1;
        }

        if($unit->moveByRail === true){
            $battle->moveRules->noZoc = true;
        }else{
            $battle->moveRules->noZoc = false;
        }

        if (!empty($b->scenario->supply) === true) {
            if ($unit->forceId == EastWest::GERMAN_FORCE) {
                $b->moveRules->zocBlocksSupply = false;
                $bias = array(5 => true, 6 => true);
                $goal = $this->germanGoal;
            } else {
                $bias = array(2 => true, 3 => true);
                $goal = $this->sovietGoal;
                $b->moveRules->zocBlocksSupply = true;

            }

            $units = $b->force->units;
            foreach ($units as $aUnit) {
                if($aUnit->class === "supply" && $aUnit->hexagon->name){
                    $goal[]= $aUnit->hexagon->name;
                }
            }
            $this->unitSupplyEffects($unit, $goal, $bias, $this->supplyLen);
        }
        if($unit->supplied){
            $battle->moveRules->oneHex = true;

        }else{
            $battle->moveRules->oneHex = false;
            $battle->moveRules->noZocZocOneHex = true;

        }
    }

    public function preRecoverUnits()
    {

        $b = Battle::getBattle();
        if($b->gameRules->mode === COMBAT_RESOLUTION_MODE){
            /*
             * remove used supply units
             */
            $units = $b->force->units;
            foreach($units as $id => $unit){
                if($unit->supplyUsed === true){
                    $b->force->eliminateUnit($unit->id);
                }
            }
        }
    }

    function isExit($args)
    {
        return false;
    }


    public function postRecoverUnit($args)
    {
        /* @var unit $unit */
        $unit = $args[0];

        $b = Battle::getBattle();
        $phase = $b->gameRules->phase;
        $id = $unit->id;
        if ($unit->forceId != $b->gameRules->attackingForceId) {
//            return;
        }
        /*
         * all units move in second movement phase
         */

        if (!empty($b->scenario->supply) === true) {
            if ($unit->forceId == EastWest::GERMAN_FORCE) {
                $b->moveRules->zocBlocksSupply = false;
                $bias = array(5 => true, 6 => true);
                $goal = $this->germanGoal;
            } else {
                $bias = array(2 => true, 3 => true);
                $goal = $this->sovietGoal;
                $b->moveRules->zocBlocksSupply = true;

            }

            $units = $b->force->units;
            foreach ($units as $aUnit) {
                if($aUnit->class === "supply" && $aUnit->hexagon->name){
                    $goal[]= $aUnit->hexagon->name;
                }
            }
            if($unit->isOnMap()) {
                $this->unitSupplyEffects($unit, $goal, $bias, $this->supplyLen);
                if ($phase === BLUE_MOVE_PHASE || $phase === RED_MOVE_PHASE) {
                    if ($unit->forceId === $b->gameRules->attackingForceId) {
                        $unit->startSupplyCheck();
                    } else {
                        $unit->killUnsupplied();
                    }
                }
            }
        }

        if ($phase == BLUE_MECH_PHASE && $unit->forceId == BLUE_FORCE  && $unit->hexagon->parent === "gameImages") {
            $unit->status = STATUS_READY;
        }
        if ($phase == RED_MECH_PHASE && $unit->forceId == RED_FORCE && $unit->hexagon->parent === "gameImages") {
            $unit->status = STATUS_READY;
        }
        if($b->gameRules->mode === COMBAT_SETUP_MODE){
            $goal = $bias = [];
            $supplyLen = 6;
            $b->moveRules->zocBlocksSupply = false;
            $b->moveRules->zocBlocksRetreat = false;
            $b->moveRules->friendlyAllowsRetreat = true;

            if($unit->forceId === EastWest::SOVIET_FORCE){
                $supplyLen = 3;
                $bias = [2 => true, 3 => true, 4 => true];
                $b->moveRules->zocBlocksSupply = true;
                $b->moveRules->zocBlocksRetreat = true;
            }
            $this->unitCombatSupplyEffects($unit, $goal, $bias, $supplyLen);
        }else{
            $unit->removeAdjustment('supply');
        }
    }

    public function postStartMovingUnit($args){
        $b = Battle::getBattle();
        list($unit) = $args;
        if($unit->status === STATUS_RETREATING){
            if($unit->forceId === EastWest::GERMAN_FORCE){
                $b->moveRules->zocBlocksRetreat = false;
            }else{
                $b->moveRules->zocBlocksRetreat = true;
            }
        }
    }

    public function checkCombatSupply($args)
    {
        /* @var unit $unit */
        $unit = $args[1];
        $goal = $args[0];

        $b = Battle::getBattle();
        $id = $unit->id;
        if ($unit->forceId != $b->gameRules->attackingForceId) {
            return;
        }
        $supplyLen = 6;
        if($b->gameRules->turn >= 6 && $b->gameRules->turn <= 8){
            $supplyLen = 1;
        }
        $b->moveRules->zocBlocksSupply = false;
        if($unit->forceId === EastWest::SOVIET_FORCE){
            $supplyLen = 3;
            $b->moveRules->zocBlocksSupply = true;
        }

        if($b->gameRules->turn === 5){
            $supplyLen = 1;
        }
        $bias = [];
        $this->unitCombatSupplyEffects($unit, $goal, $bias, $supplyLen);
    }

    public function playerTurnChange($arg)
    {
        parent::playerTurnChange($arg);
        $attackingId = $arg[0];
        $battle = Battle::getBattle();
        $mapData = $battle->mapData;
        $vp = $this->victoryPoints;
        $specialHexes = $mapData->specialHexes;
        $gameRules = $battle->gameRules;

        if ($gameRules->phase == BLUE_MECH_PHASE || $gameRules->phase == RED_MECH_PHASE) {
            $gameRules->flashMessages[] = "@hide crt";
        }
        if ($attackingId == EastWest::GERMAN_FORCE) {
            if($gameRules->turn <= $gameRules->maxTurn){
                $gameRules->flashMessages[] = "German Player Turn";
                $gameRules->replacementsAvail = 1;
            }
        }
        if ($attackingId == EastWest::SOVIET_FORCE) {
            $gameRules->flashMessages[] = "Soviet Player Turn";
            $gameRules->replacementsAvail = 6;
        }

        /*only get special VPs' at end of first Movement Phase */
        $this->victoryPoints = $vp;
    }
}