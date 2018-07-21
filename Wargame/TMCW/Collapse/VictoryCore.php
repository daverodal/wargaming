<?php
namespace Wargame\TMCW\Collapse;
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

    const VITEBSK = 4606;

    public $sovietGoal;
    public $germanGoal;
    public $ungarrisoned;

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

    function unitCombatSupplyEffects($unit, $goal, $bias, $supplyLen){
return;
        $b = Battle::getBattle();

        if ($unit->hexagon->name) {
            $unit->supplied = $b->moveRules->calcSupply($unit->id, $goal, $bias, $supplyLen);
        } else {
            return;
        }

        if (!$unit->supplied) {
//            $unit->addAdjustment('movement','floorHalfMovement');

        }
        if ($unit->supplied) {
            $unit->removeAdjustment('movement');
        }

        if (( $unit->forceId == $b->gameRules->attackingForceId) && !$unit->supplied) {
//            $unit->addAdjustment('supply','half');
//            $b->combatRules->recalcCombat($unit->id);
        }else{
            $unit->removeAdjustment('supply');
            $b->combatRules->recalcCombat($unit->id);
        }

    }

    function __construct($data)
    {
        parent::__construct($data);
        if ($data) {
            $this->germanGoal = $data->victory->germanGoal;
            $this->sovietGoal = $data->victory->sovietGoal;
            $this->ungarrisoned = $data->victory->ungarrisoned;
        } else {
            $this->germanGoal = [];
            $this->sovietGoal = [];
            $this->victoryPoints[3] = 0;
            $this->ungarrisoned = [];
        }
    }

    public function vetoPhaseChange(){
        /* @var $battle Collapse */
        $battle = Battle::getBattle();
        if($battle->gameRules->phase === RED_DEPLOY_PHASE){
            $good = $this->enoughGarrisons();
            if(!$good){
                $battle->gameRules->flashMessages[] = "required garrisons see red hexes";
            }
            return !$good;
        }
        return false;
    }

    public function getUnGarrisoned(){
        $cities = [self::VITEBSK, 4611, 4615, 4121, 2527, 2310, 1616, 2901, 3316];
        $b = BATTLE::getBattle();
        $ungarrisoned = [];
        foreach($cities as $city){
            /* @var MapHex $mapHex */
            $mapHex = $b->mapData->getHex($city);

            if($city === self::VITEBSK){
                $occupied = $mapHex->isOccupied(2, 3);
            }else{
                $occupied = $mapHex->isOccupied(2, 1);
            }
            if(!$occupied){
                $ungarrisoned[] = $city;
                $this->requireGarrison($city);
            }else{
                $this->unRequiredGarrison($city);
            }
        }
        $this->ungarrisoned = $ungarrisoned;
        return $ungarrisoned;
    }
    public function enoughGarrisons(){

        $cities = $this->getUnGarrisoned();
        return count($cities) == 0;
    }
    public function save()
    {
        $ret = parent::save();
        $ret->germanGoal = $this->germanGoal;
        $ret->sovietGoal = $this->sovietGoal;
        $ret->ungarrisoned = $this->ungarrisoned;
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
////            if ($forceId == Collapse::SOVIET_FORCE) {
////                $this->victoryPoints = "The Soviets hold Kiev";
////            }
////            if ($forceId == Collapse::GERMAN_FORCE) {
////                $this->victoryPoints = "The Germans hold Kiev";
////            }
////        }
//    }
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
        /* @var $unit SimpleUnit */
        $unit = $args[0];

        $vp = $unit->damage;
        if($unit->class == 'mech'){
            if($vp == 1){
                /* bg */
                $vp *= 5;
            }else{
                $vp *= 3;
            }
        }
        if ($unit->forceId == Collapse::GERMAN_FORCE) {
            $victorId = Collapse::SOVIET_FORCE;

            $this->victoryPoints[$victorId] += $vp;
            $hex = $unit->hexagon;
            $battle = Battle::getBattle();
            if(empty($battle->mapData->specialHexesVictory->{$hex->name})){
                $battle->mapData->specialHexesVictory->{$hex->name} = '';
            }
            $battle->mapData->specialHexesVictory->{$hex->name} .= "<span class='sovietVictoryPoints'>+$vp</span>";
        } else {
            $victorId = Collapse::GERMAN_FORCE;

            $hex  = $unit->hexagon;
            $battle = Battle::getBattle();
            if(empty($battle->mapData->specialHexesVictory->{$hex->name})){
                $battle->mapData->specialHexesVictory->{$hex->name} = '';
            }
            $battle->mapData->specialHexesVictory->{$hex->name} .= "<span class='germanVictoryPoints'>+$vp</span>";
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
//        if ($battle->mapData->getSpecialHex($kiev) === Collapse::SOVIET_FORCE) {
//            $battle->gameRules->flashMessages[] = "Soviet Player Wins";
//        }else{
//            $battle->gameRules->flashMessages[] = "German Player Wins";
//        }
        $battle->gameRules->flashMessages[] = "Nobody Wins";

        $this->gameOver = true;
        return true;
    }

    public function unRequiredGarrison($hexName)
    {

        /* @var $battle Collapse */
        $battle = Battle::getBattle();
        $battle->mapData->removeMapSymbol($hexName, "spotted");
    }

    public function requireGarrison($hexName)
    {

        /* @var $battle Collapse */
        $battle = Battle::getBattle();
        $symbol = new stdClass();
        $symbol->type = 'Spotted';
        $symbol->image = 'spotted.svg';
        $symbol->class = 'row-hex';
        $symbols = new stdClass();
        foreach ([$hexName] as $id) {
            $symbols->$id = $symbol;
        }
        $battle->mapData->setMapSymbols($symbols, "spotted");
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

        if($gameRules->phase === RED_DEPLOY_PHASE && $gameRules->turn === 1){
            $gameRules->flashMessages[] = "@show deployWrapper";
            $freeUnits = $gameRules->option;
            if($freeUnits) {
                foreach ($freeUnits as $unitId) {
                    $force->units[$unitId]->reinforceZone = 'B';
                }
            }
            $this->enoughGarrisons();
        }
//
//        foreach ($theUnits as $id => $unit) {
//            $unit->railMove(false);
//        }
        if ($gameRules->phase == RED_COMBAT_PHASE || $gameRules->phase == BLUE_COMBAT_PHASE) {
            $gameRules->flashMessages[] = "@hide deployWrapper";
        } else {
            $gameRules->flashMessages[] = "@hide crt";

            /* Restore all un-supplied strengths */
            $force = $battle->force;
//            $this->restoreAllCombatEffects($force);
        }

        if ($gameRules->phase == BLUE_MOVE_PHASE || $gameRules->phase == RED_MOVE_PHASE) {
            $gameRules->flashMessages[] = "@hide deadpile";
            if (isset($battle->force->reinforceTurns->$turn->$forceId)) {
                $gameRules->flashMessages[] = "@show deployWrapper";
                $gameRules->flashMessages[] = "Reinforcements have been moved to the Deploy/Staging Area";
            }

            foreach ($theUnits as $id => $unit) {
                $unit->railMove(false);

                if ($unit->status == STATUS_CAN_REINFORCE && $unit->reinforceTurn <= $battle->gameRules->turn && $unit->hexagon->parent != "deployBox") {
                    $theUnits[$id]->hexagon->parent = "deployBox";
                }
            }
        }
    }

    public function unitDeployed($arg){
        $unit = $arg[0];
        /* @var $b Collapse */
        $b  = Battle::getBattle();

        if($b->gameRules->phase === RED_DEPLOY_PHASE){
            $this->enoughGarrisons();
        }

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
        $this->germanGoal = array_merge($b->moveRules->calcRoadSupply(Collapse::GERMAN_FORCE, 207, $germanBias));
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
        if ($unit->forceId == Collapse::GERMAN_FORCE) {
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
            if ($unit->forceId == Collapse::GERMAN_FORCE) {
                $bias = array(5 => true, 6 => true);
                $goal = $this->germanGoal;
            } else {
                $bias = array(2 => true, 3 => true);
                $goal = $this->sovietGoal;

            }

        }

        if($b->gameRules->mode === COMBAT_SETUP_MODE){
            $goal = $bias = [];

            if($unit->forceId === Collapse::SOVIET_FORCE){
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
        if($unit->forceId === Collapse::SOVIET_FORCE){
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
        $gameRules = $battle->gameRules;

        if ($gameRules->phase == BLUE_MECH_PHASE || $gameRules->phase == RED_MECH_PHASE) {
            $gameRules->flashMessages[] = "@hide crt";
        }

    }
}