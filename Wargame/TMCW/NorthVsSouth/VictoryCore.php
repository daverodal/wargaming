<?php
namespace Wargame\TMCW\NorthVsSouth;
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
    public $southernGoal;
    public $northernGoal;
    public $unsuppliedDefenderHalved = false;

    function unitSupplyEffects($unit, $goal, $bias, $supplyLen){
        $b = Battle::getBattle();
        $id = $unit->id;

        if ($unit->hexagon->parent === "gameImages") {
            $unit->supplied = $b->moveRules->calcSupply($unit->id, $goal, $bias, $supplyLen);
        } else {
            return;
        }

        if (!$unit->supplied) {
            $unit->addAdjustment('movement','floorHalfMovement');

        }
        if ($unit->supplied) {
            $unit->removeAdjustment('movement');
        }

        if (!$unit->supplied) {
            $unit->addAdjustment('supply','half');
//            $b->combatRules->recalcCombat($unit->id);
        }else{
            $unit->removeAdjustment('supply');
//            $b->combatRules->recalcCombat($unit->id);
        }
    }

    function __construct($data)
    {
        parent::__construct($data);
        if ($data) {
            $this->northernGoal = $data->victory->northernGoal ?? [];
            $this->southernGoal = $data->victory->southernGoal ?? [];
        } else {
            $this->northernGoal = [];
            $this->southernGoal = [];
            $this->victoryPoints[3] = 0;
        }
    }

    public function vetoPhaseChange(){

        return false;
    }

    public function save()
    {
        $ret = parent::save();
        $ret->northernGoal = $this->northernGoal ?? [];
        $ret->southernGoal = $this->southernGoal ?? [];
        return $ret;
    }

    public function setSupplyLen($supplyLen)
    {
        $this->supplyLen = $supplyLen[0];
    }

    public function reduceUnit($args)
    {
        /* @var $unit SimpleUnit */
        $unit = $args[0];

        $vp = $unit->damage;
        $hex  = $unit->hexagon;
        if ($unit->forceId == NorthVsSouth::SOUTHERN_FORCE) {
            $victorId = NorthVsSouth::NORTHERN_FORCE;

            $this->victoryPoints[$victorId] += $vp;
            $hex = $unit->hexagon;
            $battle = Battle::getBattle();
            if(empty($battle->mapData->specialHexesVictory->{$hex->name})){
                $battle->mapData->specialHexesVictory->{$hex->name} = '';
            }
            $battle->mapData->specialHexesVictory->{$hex->name} .= "<span class='northernVictoryPoints'>+$vp</span>";
        } else {
            $victorId = NorthVsSouth::SOUTHERN_FORCE;

            $hex  = $unit->hexagon;
            $battle = Battle::getBattle();
            if(empty($battle->mapData->specialHexesVictory->{$hex->name})){
                $battle->mapData->specialHexesVictory->{$hex->name} = '';
            }
            $battle->mapData->specialHexesVictory->{$hex->name} .= "<span class='southernVictoryPoints'>+$vp</span>";
            $this->victoryPoints[$victorId] += $vp;
        }
    }


    protected function checkVictory($attackingId, $battle){

        return false;
    }

    public function incrementTurn()
    {


    }

    public function gameEnded()
    {
        $battle = Battle::getBattle();
//        $kiev = $battle->specialHexC[0];
//        if ($battle->mapData->getSpecialHex($kiev) === NorthVsSouth::NORTHERN_FORCE) {
//            $battle->gameRules->flashMessages[] = "Soviet Player Wins";
//        }else{
//            $battle->gameRules->flashMessages[] = "German Player Wins";
//        }
        $battle->gameRules->flashMessages[] = "Nobody Wins";

        $this->gameOver = true;
        return true;
    }
    public function preStartMovingUnit($arg)
    {
        $unit = $arg[0];
        $battle = Battle::getBattle();
            $battle->moveRules->enterZoc = "stop";
            $battle->moveRules->exitZoc = 0;
            $battle->moveRules->noZocZoc = false;
    }
    public function postReinforceZoneNames($args)
    {
        $battle = Battle::getBattle();
        /* @var MapData $mapData */
        $mapData = $battle->mapData;
        $specialHexes = $battle->specialHexB;


        list($zoneNames, $unit, $hexagon) = $args;
        $zones = [];

        if ($unit->nationality === "southern") {
            foreach ($specialHexes as $specialHex) {
                if ($mapData->getSpecialHex($specialHex) == NorthVsSouth::SOUTHERN_FORCE) {

                }
            }
        }

        if ($unit->nationality === "northern") {
            $specialHexes = $battle->specialHexA;
            foreach ($specialHexes as $specialHex) if ($mapData->getSpecialHex($specialHex) == NorthVsSouth::NORTHERN_FORCE) {
                    if($specialHex == $hexagon->getNumber()){
                        $zones[] = $specialHex;
                    }
                    $mapHex = $mapData->getHex($specialHex);
                    $neighbors = $mapHex->neighbors;
                    foreach($neighbors as $neighbor){
                        $newHexNum = $neighbor;
                        if(is_object($neighbor)){
                            $newHexNum = $neighbor->hexNum;
                        }
                        if ($battle->terrain->terrainIsHex($newHexNum, "blocked")) {
                            continue;
                        }
                        if($newHexNum == $hexagon->getNumber()){
                            $zones[] = $unit->reinforceZone;
                        }
                    }
                }
            }
           return [$zones];
    }


    public function postReinforceZones($args)
    {
        $battle = Battle::getBattle();

        list($zones, $unit) = $args;
        if ($unit->nationality === "southern") {
            $battle = Battle::getBattle();
            /* @var MapData $mapData */
            $mapData = $battle->mapData;
            $specialHexes = $battle->specialHexB;

            $zones = [];
            foreach ($specialHexes as $specialHex) {
                if ($mapData->getSpecialHex($specialHex) == NorthVsSouth::SOUTHERN_FORCE) {
                    $zones[] = new \Wargame\ReinforceZone($specialHex, $specialHex);
                    $mapHex = $mapData->getHex($specialHex);
                    $neighbors = $mapHex->neighbors;
                    foreach($neighbors as $neighbor){
                        $newHexNum = $neighbor;
                        if(is_object($neighbor)){
                            $newHexNum = $neighbor->hexNum;
                        }
                        if ($battle->terrain->terrainIsHex($newHexNum, "blocked")) {
                            continue;
                        }
                        $zones[] = new \Wargame\ReinforceZone($newHexNum, $newHexNum);
                    }

                }
            }
        }
        if ($unit->nationality === "northern") {
            $battle = Battle::getBattle();
            /* @var MapData $mapData */
            $mapData = $battle->mapData;
            $specialHexes = $battle->specialHexA;

            $zones = [];
            foreach ($specialHexes as $specialHex) {
                if ($mapData->getSpecialHex($specialHex) == NorthVsSouth::NORTHERN_FORCE) {
                    $zones[] = new \Wargame\ReinforceZone($specialHex, $specialHex);
                    $mapHex = $mapData->getHex($specialHex);
                    $neighbors = $mapHex->neighbors;
                    foreach($neighbors as $neighbor){
                        $newHexNum = $neighbor;
                        if(is_object($neighbor)){
                            $newHexNum = $neighbor->hexNum;
                        }
                        if ($battle->terrain->terrainIsHex($newHexNum, "blocked")) {
                            continue;
                        }
                        $zones[] = new \Wargame\ReinforceZone($newHexNum, $newHexNum);
                    }

                }
            }
        }
        return array($zones);
    }


    public function phaseChange()
    {

        /* @var $battle NorthVsSouth */
        $battle = Battle::getBattle();
        /* @var $gameRules \Wargame\GameRules */
        $gameRules = $battle->gameRules;
        $forceId = $gameRules->attackingForceId;
        $turn = $gameRules->turn;
        $force = $battle->force;
        $theUnits = $battle->force->units;

        if($gameRules->phase === RED_DEPLOY_PHASE && $gameRules->turn === 1){
            $gameRules->flashMessages[] = "@show deployWrapper";
        }

        if($gameRules->mode === REPLACING_MODE){
            $gameRules->flashMessages[] = "@show deadpile";
            $gameRules->flashMessages[] = "@hide deployWrapper";
        }else{
            $gameRules->flashMessages[] = "@hide deadpile";
        }
        if ($gameRules->phase == RED_COMBAT_PHASE || $gameRules->phase == BLUE_COMBAT_PHASE) {
            $gameRules->flashMessages[] = "@hide deployWrapper";
        } else {
            $gameRules->flashMessages[] = "@hide crt";
        }

        if ($gameRules->phase == BLUE_MOVE_PHASE || $gameRules->phase == RED_MOVE_PHASE) {
            $gameRules->flashMessages[] = "@hide deployWrapper";

            foreach ($theUnits as $id => $unit) {
                $unit->railMove(false);

                if ($unit->status == STATUS_CAN_REINFORCE && $unit->reinforceTurn <= $battle->gameRules->turn && $unit->hexagon->parent != "deployBox") {
                    $theUnits[$id]->hexagon->parent = "deployBox";
                }
            }
        }
    }

    public function combatResolutionMode(){
        $b = Battle::getBattle();
        $force = $b->force;
        $units = $force->units;
        foreach($b->combatRules->combats as $combatId => $combat){
            foreach($combat->attackers as $attackerId => $fake){
                $units[$attackerId]->tried = true;
            }
            foreach($combat->defenders as $defenderId => $fake){
                $units[$defenderId]->tried = true;
            }
            $b->combatRules->crt->setCombatIndex($combatId);
        }
    }

    public function unitDeployed($arg){
        $unit = $arg[0];
        /* @var $b NorthVsSouth */
        $b  = Battle::getBattle();
    }

    public function preRecoverUnits()
    {

        $b = Battle::getBattle();
        $units = $b->force->units;

        if($b->gameRules->mode === COMBAT_RESOLUTION_MODE){
            /*
             * remove used supply units
             */
        }

        $this->northernGoal = [607, 610, 1311, 1613, 1706, 1705];
        $this->southernGoal = [921,624,224, 1321, 1421, 1424];
        $bef = microtime(true);

//        foreach($units as $id => $unit){
//            echo "Recover unit ";
//            $unit->recover();
//        }
        $aft = microtime(true);
        $dur = $aft - $bef;
//        echo "D: $dur :D ";
    }

    public function preRecoverUnit($arg){
//        echo "Pre REc ";
        list($unit) = $arg;
//        echo $unit->id." ";
        if ($unit->forceId == NorthVsSouth::NORTHERN_FORCE) {
            $bias = array( 0 => true, 1 => true);
            $goal = $this->northernGoal;
        } else {
            $bias = array( 3 => true, 4 => true);
            $goal = $this->southernGoal;

        }
        $this->unitSupplyEffects($unit, $goal, $bias, 15);

    }


    function isExit($args)
    {
        return false;
    }


//    public function postRecoverUnit($args)
//    {
//        /* @var unit $unit */
//        $unit = $args[0];
//
//        /* @var $b NorthVsSouth */
//        $b = Battle::getBattle();
//        $gameRules = $b->gameRules;
//        $phase = $b->gameRules->phase;
//        $id = $unit->id;
//        /*
//         * all units move in second movement phase
//         */
//
//        if (!empty($b->scenario->supply) === true) {
//            if ($unit->forceId == NorthVsSouth::SOUTHERN_FORCE) {
//                $bias = array(1=> true, 5 => true, 0 => true);
//                $goal = $this->northernGoal;
//            } else {
//                $bias = array(2 => true, 3 => true, 4 => true);
//                $goal = $this->southernGoal;
//
//            }
//
//        }
//
////        if($b->gameRules->mode === COMBAT_SETUP_MODE){
////            $this->unitCombatSupplyEffects($unit, $goal, $bias, $this->supplyLen);
////        }
//    }

    public function checkCombatSupply($args)
    {
        /* @var unit $unit */
        $unit = $args[1];
        $goal = $args[0];

        $bias = [];
        $this->unitCombatSupplyEffects($unit, $goal, $bias, $this->supplyLen);
    }




    public function playerTurnChange($arg)
    {

        parent::playerTurnChange($arg);
        $attackingId = $arg[0];
        $battle = Battle::getBattle();
        $mapData = $battle->mapData;
        $vp = $this->victoryPoints;
        $specialHexes = $mapData->specialHexes;
        /* @var $gameRules \Wargame\GameRules */
        $gameRules = $battle->gameRules;

        if ($gameRules->phase == BLUE_MECH_PHASE || $gameRules->phase == RED_MECH_PHASE) {
            $gameRules->flashMessages[] = "@hide crt";
        }

        $gameRules->replacementsAvail = 2;

        if($attackingId === NorthVsSouth::NORTHERN_FORCE){
            $gameRules->replacementsAvail = 3;
            if($gameRules->turn === 1){
                $gameRules->replacementsAvail = 1;
                $gameRules->flashMessages[] = "Only one replacement available on turn one.";
            }
        }
    }
}