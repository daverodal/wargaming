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
    public $sovietGoal;
    public $germanGoal;

    function unitSupplyEffects($unit, $goal, $bias, $supplyLen){
        $b = Battle::getBattle();
        $id = $unit->id;

        if ($unit->hexagon->parent === "gameImages") {
            $unit->supplied = true; //$b->moveRules->calcSupply($unit->id, $goal, $bias, $supplyLen);
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
            $this->germanGoal = $data->victory->germanGoal;
            $this->sovietGoal = $data->victory->sovietGoal;
        } else {
            $this->germanGoal = [];
            $this->sovietGoal = [];
            $this->victoryPoints[3] = 0;
        }
    }

    public function vetoPhaseChange(){

        return false;
    }

    public function save()
    {
        $ret = parent::save();
        $ret->germanGoal = $this->germanGoal;
        $ret->sovietGoal = $this->sovietGoal;
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
    public function preStartMovingUnit($arg)
    {
        $unit = $arg[0];
        $b = $battle = Battle::getBattle();
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

        $germanBias = array(5 => true, 6 => true);
        $sovietBias = array(2 => true, 3 => true);
        $this->germanGoal = array_merge($b->moveRules->calcRoadSupply(NorthVsSouth::SOUTHERN_FORCE, 207, $germanBias));
        $this->sovietGoal = [];
        foreach($units as $id => $unit){
            $unit->recover();
            if($unit->class == 'railhead' && $unit->hexagon->parent == 'gameImages'){
                $this->sovietGoal[] = $unit->hexagon->name;
            }
        }
    }

    public function preRecoverUnit($arg){
        list($unit) = $arg;
        if ($unit->forceId == NorthVsSouth::SOUTHERN_FORCE) {
            $bias = array(5 => true, 6 => true);
            $goal = $this->germanGoal;
        } else {
            $bias = array(2 => true, 3 => true);
            $goal = $this->sovietGoal;

        }
        $this->unitSupplyEffects($unit, $goal, $bias, 10);
    }


    function isExit($args)
    {
        return false;
    }


    public function postRecoverUnit($args)
    {
        /* @var unit $unit */
        $unit = $args[0];

        /* @var $b NorthVsSouth */
        $b = Battle::getBattle();
        $gameRules = $b->gameRules;
        $phase = $b->gameRules->phase;
        $id = $unit->id;
        /*
         * all units move in second movement phase
         */

        if (!empty($b->scenario->supply) === true) {
            if ($unit->forceId == NorthVsSouth::SOUTHERN_FORCE) {
                $bias = array(5 => true, 6 => true);
                $goal = $this->germanGoal;
            } else {
                $bias = array(2 => true, 3 => true);
                $goal = $this->sovietGoal;

            }

        }

        if($b->gameRules->mode === COMBAT_SETUP_MODE){
            $goal = $bias = [];

            if($unit->forceId === NorthVsSouth::NORTHERN_FORCE){
                $bias = [2 => true, 3 => true, 4 => true];

            }
//            $this->unitCombatSupplyEffects($unit, $goal, $bias, $this->supplyLen);
        }
    }

    public function checkCombatSupply($args)
    {
        return;
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
        if($unit->forceId === NorthVsSouth::NORTHERN_FORCE){
            $supplyLen = 3;
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
        /* @var $gameRules \Wargame\GameRules */
        $gameRules = $battle->gameRules;

        if ($gameRules->phase == BLUE_MECH_PHASE || $gameRules->phase == RED_MECH_PHASE) {
            $gameRules->flashMessages[] = "@hide crt";
        }

        $gameRules->replacementsAvail = 2;

        if($attackingId === NorthVsSouth::NORTHERN_FORCE){
            $gameRules->replacementsAvail = 3;
        }
    }
}