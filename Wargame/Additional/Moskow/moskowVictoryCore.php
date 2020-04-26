<?php
namespace Wargame\Additional\Moskow;
use \Wargame\Battle;
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

class moskowVictoryCore extends \Wargame\TMCW\victoryCore
{
    public $sovietGoal;
    public $germanGoal;

    public $gameOver = false;


    function __construct($data)
    {
        parent::__construct($data);
        if ($data) {
            $this->germanGoal = $data->victory->germanGoal;
            $this->sovietGoal = $data->victory->sovietGoal;
        } else {
            $this->victoryPoints = "The Soviets hold Moskow";
            $this->germanGoal = $this->sovietGoal = [];
        }
    }

    public function setSupplyLen($supplyLen)
    {
        $this->supplyLen = $supplyLen[0];
    }

    public function save()
    {
        $ret = parent::save();
        $ret->germanGoal = $this->germanGoal;
        $ret->sovietGoal = $this->sovietGoal;
        return $ret;
    }

    public function specialHexChange($args)
    {
        $battle = Battle::getBattle();
        list($mapHexName, $forceId) = $args;

        if(in_array($mapHexName, $battle->specialHexC)){

            if ($forceId == Moskow::SOVIET_FORCE) {
                $this->victoryPoints = "The Soviets hold Moskow";
            }
            if ($forceId == Moskow::GERMAN_FORCE) {
                $this->victoryPoints = "The Germans hold Moskow";
            }
        }
    }

    public function postReinforceZones($args)
    {
        list($zones, $unit) = $args;

        $forceId = $unit->forceId;
        if($unit->forceId == Moskow::GERMAN_FORCE){
            $zones = $this->germanGoal;
        }else{
            $zones = $this->sovietGoal;
        }
        $reinforceZones = [];
        foreach($zones as $zone){
            $reinforceZones[] = new \Wargame\ReinforceZone($zone, $zone);
        }
        $battle = Battle::getBattle();

        $specialHexes = $battle->mapData->specialHexes;
        foreach($specialHexes as $hexNum => $specialHex){
            if($specialHex == $forceId){
                $reinforceZones[] = new \Wargame\ReinforceZone($hexNum, $hexNum);
            }
        }
        return array($reinforceZones);
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
        if ($unit->forceId == 1) {
//            $victorId = 2;
//            $this->victoryPoints[$victorId] += $vp;
//            $hex = $unit->hexagon;
//            $battle = Battle::getBattle();
//            $battle->mapData->specialHexesVictory->{$hex->name} = "<span class='loyalistVictoryPoints'>+$vp vp</span>";
        } else {
//            $victorId = 1;
//            $hex  = $unit->hexagon;
//            $battle = Battle::getBattle();
//            $battle->mapData->specialHexesVictory->{$hex->name} = "+$vp vp";
//            $this->victoryPoints[$victorId] += $vp;
        }
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
        $moskow = $battle->specialHexC[0];
        if ($battle->mapData->getSpecialHex($moskow) === Moskow::SOVIET_FORCE) {
            $battle->gameRules->flashMessages[] = "Soviet Player Wins";
        }else{
            $battle->gameRules->flashMessages[] = "German Player Wins";
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
            if (!empty($battle->force->reinforceTurns->$turn->$forceId)) {
                $gameRules->flashMessages[] = "@show deployWrapper";
                $gameRules->flashMessages[] = "Reinforcements have been moved to the Deploy/Staging Area";
            }
        }
    }

    public function preRecoverUnits()
    {
        $germanGoal = $sovietGoal = [];

        /* German goal is west Edge */
        for($i = 1; $i <= 17;$i++){
            $germanGoal[] = 100 + $i;
        }
        $this->germanGoal = $germanGoal;

        /* Soviet goal is west Edge */
        for($i = 1; $i <= 17;$i++){
            $sovietGoal[] = 2700 + $i;
        }
        $this->sovietGoal = $sovietGoal;

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
        $id = $unit->id;
        if ($unit->forceId != $b->gameRules->attackingForceId) {
//            return;
        }
        if (!empty($b->scenario->supply) === true) {
            if ($unit->forceId == Moskow::GERMAN_FORCE) {
                $bias = array(5 => true, 6 => true, 1 => true);
                $goal = $this->germanGoal;
            } else {
                $bias = array(2 => true, 3 => true, 4 => true);
                $goal = $this->sovietGoal;
            }
            $this->unitSupplyEffects($unit, $goal, $bias, $this->supplyLen);

            if($b->gameRules->turn >= 4 && $b->gameRules->turn <= 6){

                if(!isset($this->movementCache->$id)) {
                    $this->movementCache->$id = $unit->maxMove;
                    $unit->maxMove = 1;
                    if($unit->forceId == Moskow::SOVIET_FORCE){
                        $unit->class = 'mudinf';
                    }
                }
                if($unit->forceId == $b->gameRules->attackingForceId){
                    $unit->addAdjustment('mud','floorHalf');
                }else{
                    $unit->removeAdjustment('mud');
                }
            }
            if($b->gameRules->turn == 7){
                if(isset($this->movementCache->$id)) {
                    $unit->maxMove = $this->movementCache->$id;
                    $unit->removeAdjustment('mud');
                    if($unit->class === 'mudinf'){
                        $unit->class = 'inf';
                    }
                }
            }
        }
    }

    public function playerTurnChange($arg)
    {
        $attackingId = $arg[0];
        $battle = Battle::getBattle();
        $scenario = $battle->scenario;
        $mapData = $battle->mapData;
        $vp = $this->victoryPoints;
        $specialHexes = $mapData->specialHexes;
        $gameRules = $battle->gameRules;

        if ($gameRules->phase == BLUE_MECH_PHASE || $gameRules->phase == RED_MECH_PHASE) {
            $gameRules->flashMessages[] = "@hide crt";
        }
        if ($attackingId == Moskow::GERMAN_FORCE) {
            if($gameRules->turn <= $gameRules->maxTurn){
                $gameRules->flashMessages[] = "German Player Turn";
                $gameRules->replacementsAvail = 1;
                if(!empty($scenario->weakGermans)) {
                    if($battle->gameRules->turn & 2 !== 0){
                        $gameRules->replacementsAvail = 0;
                    }
                }
                if($gameRules->turn == 4){
                    $gameRules->flashMessages[] = "Mud In Effect ";
                }
            }
        }
        if ($attackingId == Moskow::SOVIET_FORCE) {
            $gameRules->flashMessages[] = "Soviet Player Turn";
            $gameRules->replacementsAvail = 8;
            if(!empty($scenario->weakSoviets)){
                $gameRules->replacementsAvail = 6;
            }
            if(!empty($scenario->veryWeakSoviets)){
                $gameRules->replacementsAvail = 4;
            }
        }

        /*only get special VPs' at end of first Movement Phase */
        $this->victoryPoints = $vp;
    }
}