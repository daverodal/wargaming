<?php
namespace Wargame\TMCW\Kiev1941;
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

class kievVictoryCore extends \Wargame\TMCW\victoryCore
{

    public $sovietGoal;
    public $germanGoal;
    public $unsuppliedDefenderHalved = true;
    public $dismissed = false;

    function __construct($data)
    {
        parent::__construct($data);
        if ($data) {
            $this->dismissed = $data->victory->dismissed;
        } else {
            $this->victoryPoints[3] = 0;
        }
        $this->germanGoal = $this->sovietGoal = [];

        /* German goal is west Edge north and south edge cols 1-12 */
        for($i = 1; $i <= 15;$i++){
            $this->germanGoal[] = 100 + $i;
        }
        for($i = 1; $i <= 12;$i++){
            $this->germanGoal[] = 15 + $i * 100;
        }
        for($i = 1; $i <= 12    ;$i++){
            $this->germanGoal[] = 1 + $i * 100;
        }
        /* Soviet goal is west Edge */
        for($i = 1; $i <= 15    ;$i++){
            $this->sovietGoal[] = 1700 + $i;
        }
    }

    public function save()
    {
        $ret = parent::save();
        $ret->dismissed = $this->dismissed;
        return $ret;
    }

    public function setSupplyLen($supplyLen)
    {
        $this->supplyLen = $supplyLen[0];
    }

//    public function specialHexChange($args)
//    {
//        $battle = Battle::getBattle();
//        list($mapHexName, $forceId) = $args;
//
////        if(in_array($mapHexName, $battle->specialHexC)){
////
////            if ($forceId == Kiev1941::SOVIET_FORCE) {
////                $this->victoryPoints = "The Soviets hold Kiev";
////            }
////            if ($forceId == Kiev1941::GERMAN_FORCE) {
////                $this->victoryPoints = "The Germans hold Kiev";
////            }
////        }
//    }

    public function postReinforceZones($args)
    {
        list($zones, $unit) = $args;

        $forceId = $unit->forceId;
        if($unit->forceId == Kiev1941::GERMAN_FORCE){
            $zones = $this->germanGoal;
        }else{
            $zones = $this->sovietGoal;
        }
        $reinforceZones = [];
        foreach($zones as $zone){
            $reinforceZones[] = new \Wargame\ReinforceZone($zone, $zone);
        }
        $battle = Battle::getBattle();

        return array($reinforceZones);
    }

    public function reduceUnit($args)
    {
        $unit = $args[0];
        $vp = $unit->damage;
        if ($unit->forceId == 1) {
            $victorId = 2;
            $this->victoryPoints[$victorId] += $vp;
            $hex = $unit->hexagon;
            $battle = Battle::getBattle();
            if(empty($battle->mapData->specialHexesVictory->{$hex->name})){
                $battle->mapData->specialHexesVictory->{$hex->name} = '';
            }
            $battle->mapData->specialHexesVictory->{$hex->name} .= "<span class='sovietVictoryPoints'>+$vp</span>";
        } else {
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
                if($unit->forceId == Kiev1941::SOVIET_FORCE){
                    if($unit->hexagon->parent == "gameImages"){
                        if($unit->supplied === false){
                            $this->victoryPoints[3] += $unit->getUnmodifiedStrength();
                        }
                    }
                }
            }
            if(!$this->dismissed && $this->victoryPoints[1] + $this->victoryPoints[3] >= 35){
                $this->dismissed = true;
                $battle->gameRules->flashMessages[] = "Budyonny Relieved.";
                $battle->gameRules->flashMessages[] = "Unsupplied units may not enter zoc or attack.";

            }
        }
    }
    protected function checkVictory($attackingId, $battle){

        $this->checkSurrounded();
        return false;
    }

    public function incrementTurn()
    {
        $battle = Battle::getBattle();

        $theUnits = $battle->force->units;
        foreach ($theUnits as $id => $unit) {

            if ($unit->status == STATUS_CAN_REINFORCE && $unit->reinforceTurn <= $battle->gameRules->turn && $unit->hexagon->parent != "deployBox") {
                $theUnits[$id]->status = STATUS_ELIMINATED;
                $theUnits[$id]->hexagon->parent = "deadpile";
            }
        }
    }

    public function gameEnded()
    {
        $battle = Battle::getBattle();
//        $kiev = $battle->specialHexC[0];
//        if ($battle->mapData->getSpecialHex($kiev) === Kiev1941::SOVIET_FORCE) {
//            $battle->gameRules->flashMessages[] = "Soviet Player Wins";
//        }else{
//            $battle->gameRules->flashMessages[] = "German Player Wins";
//        }

        if($this->victoryPoints[Kiev1941::GERMAN_FORCE] > 49){
            $this->winner = Kiev1941::GERMAN_FORCE;
            $this->gameOver = true;
            $battle->gameRules->flashMessages[] = "Germans Wins";
        }else{
            $this->winner = Kiev1941::SOVIET_FORCE;
            $this->gameOver = true;
            $battle->gameRules->flashMessages[] = "Soviets Wins";
        }

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

        if ($turn == 1 && $gameRules->phase == BLUE_MOVE_PHASE) {
            /* first 4 units gaga */
            $supply = [];
            $battle->terrain->reinforceZones = [];
            $units = $force->units;
            $num = count($units);
            for ($i = 0; $i < $num; $i++) {
                $unit = $units[$i];
                if ($unit->forceId == BLUE_FORCE && $unit->hexagon->parent === "gameImages") {
                    $supply[$unit->hexagon->name] = BLUE_FORCE;
                }
            }
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
        }
    }

    public function preStartMovingUnit($arg)
    {
        $unit = $arg[0];
        $battle = Battle::getBattle();
        if ($unit->class != 'mech') {
            $battle->moveRules->enterZoc = "stop";
            $battle->moveRules->exitZoc = 1;
            $battle->moveRules->noZocZoc = true;
        } else {

            $battle->moveRules->enterZoc = 3;
            $battle->moveRules->exitZoc = 2;
            $battle->moveRules->noZocZocOneHex = true;
            $battle->moveRules->noZocZoc = false;

        }
        if($this->dismissed){
            if($battle->gameRules->phase == RED_MOVE_PHASE && $unit->supplied !== true) {
                $battle->moveRules->noZoc = true;
            }
            else{
                $battle->moveRules->noZoc = false;
            }

        }

    }

    public function preRecoverUnits()
    {

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


        if($unit->nationality === "first-panzer-army"){
            if($b->gameRules->turn < 3){
                $unit->status = STATUS_UNAVAIL_THIS_PHASE;
                return;
            }
        }
        $id = $unit->id;
        if ($b->gameRules->turn == 1 && $b->gameRules->phase == RED_MOVE_PHASE && $unit->status == STATUS_READY) {
            if($b->force->unitIsZoc($unit->id)) {

                $unit->status = STATUS_UNAVAIL_THIS_PHASE;
            }
        }

        if ($unit->forceId != $b->gameRules->attackingForceId) {
//            return;
        }
        if (!empty($b->scenario->supply) === true) {
            if ($unit->forceId == Kiev1941::GERMAN_FORCE) {
                $bias = array(5 => true, 6 => true);
                $goal = $this->germanGoal;
            } else {
                $bias = array(2 => true, 3 => true);
                $goal = $this->sovietGoal;
            }
            $this->unitSupplyEffects($unit, $goal, $bias, $this->supplyLen);
        }
        $this->checkSurrounded();
        if($this->dismissed){
            if ($b->gameRules->phase == RED_COMBAT_PHASE && $unit->supplied !== true) {
                $unit->status = STATUS_UNAVAIL_THIS_PHASE;
            }
        }
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
        if ($attackingId == Kiev1941::GERMAN_FORCE) {
            if($gameRules->turn <= $gameRules->maxTurn){
                $gameRules->flashMessages[] = "German Player Turn";
                $gameRules->replacementsAvail = 1;
            }
        }
        if ($attackingId == Kiev1941::SOVIET_FORCE) {
            $gameRules->flashMessages[] = "Soviet Player Turn";
            $gameRules->replacementsAvail = 4;
            if($gameRules->turn === 1){
                $gameRules->flashMessages[] = "Stalin orders no retreat first turn, all units in zoc cannot move.";
            }
        }

        /*only get special VPs' at end of first Movement Phase */
        $this->victoryPoints = $vp;
    }
}