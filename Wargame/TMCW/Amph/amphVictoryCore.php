<?php
namespace Wargame\TMCW\Amph;
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
//include_once "victoryCore.php";

class amphVictoryCore extends \Wargame\TMCW\victoryCore
{
    private $landingZones;
    private $airdropZones;
//    private $scienceCenterDestroyed = false;

    const GOAL_VP = 12;

    function __construct($data)
    {
        parent::__construct($data);
        if ($data) {
            $this->landingZones = $data->victory->landingZones;
            $this->airdropZones = $data->victory->airdropZones;
//            $this->scienceCenterDestroyed = $data->victory->scienceCenterDestroyed;
        } else {
            $this->landingZones = [];
            $this->airdropZones = [];
            $this->victoryPoints = [0,0,0];
        }
    }

    public function setSupplyLen($supplyLen)
    {
        $this->supplyLen = $supplyLen[0];
    }

    public function save()
    {
        $ret = parent::save();
        $ret->landingZones = $this->landingZones;
        $ret->airdropZones = $this->airdropZones;
//        $ret->scienceCenterDestroyed = $this->scienceCenterDestroyed;
        return $ret;
    }

    public function enterMapSymbol($args)
    {
        $battle = Battle::getBattle();
        /* @var $mapData MapData */
        $mapData = $battle->mapData;
        /* @var $unit MovableUnit */
        list($mapHexName, $unit) = $args;

        if ($unit->forceId == Amph::LOYALIST_FORCE) {
            $mapData->removeMapSymbol($mapHexName, "supply");
            $newZones = [];
            $removed = "";
            foreach($this->airdropZones as $zone){
                if($zone == $mapHexName){
                    $removed = "Airdrop Zone ";
                    continue;
                }
                $newZones[] = $zone;

            }
            $this->airdropZones = $newZones;
            $newZones = [];
            foreach($this->landingZones as $zone){
                if($zone == $mapHexName){
                    $removed = "Beach Landing Zone ";
                    continue;
                }
                $newZones[] = $zone;

            }
            $this->landingZones = $newZones;
            $mapData->specialHexesVictory->$mapHexName = "<span class='loyalistVictoryPoints'>$removed Destroyed</span>";

        }


    }
    public function specialHexChange($args)
    {
        $battle = Battle::getBattle();

        list($mapHexName, $forceId) = $args;

//        if ($mapHexName == 1807 && $forceId == Amph::REBEL_FORCE) {
//            $this->scienceCenterDestroyed;
//            $battle->mapData->specialHexesVictory->$mapHexName = "<span class='rebelVictoryPoints'>Marine Science Facility Destroyed</span>";
//            $battle->gameRules->flashMessages[] = "Rebel units may now withdraw from beachheads";
//        }
        if ($forceId == Amph::LOYALIST_FORCE) {
            $newLandings = [];
            foreach ($this->landingZones as $landingZone) {
                if ($landingZone == $mapHexName) {
                    $battle->mapData->specialHexesVictory->$mapHexName = "<span class='loyalistVictoryPoints'>Beachhead Destroyed</span>";
                    $battle->mapData->removeSpecialHex($mapHexName);
                    unset($battle->mapData->specialHexesChanges->$mapHexName);
                    continue;
                }
                $newLandings[] = $landingZone;
            }
            $this->landingZones = $newLandings;

            $newAirdrops = [];
            foreach ($this->airdropZones as $airdropZone) {
                if ($airdropZone == $mapHexName) {
                    $battle->mapData->specialHexesVictory->$mapHexName = "<span class='loyalistVictoryPoints'>Airdrop zone Destroyed</span>";
                    $battle->mapData->removeSpecialHex($mapHexName);
                    unset($battle->mapData->specialHexesChanges->$mapHexName);
                    continue;
                }
                $newAirdrops[] = $airdropZone;
            }
            $this->airdropZones = $newAirdrops;
        }

        if(in_array($mapHexName,$battle->specialHexA)){
            $vp = self::GOAL_VP;

            $prevForceId = $battle->mapData->specialHexes->$mapHexName;
            if ($forceId == Amph::REBEL_FORCE) {
                $this->victoryPoints[Amph::REBEL_FORCE]  += $vp;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='rebelVictoryPoints'>+$vp Rebel vp</span>";
//                $this->victoryPoints[Amph::LOYALIST_FORCE] -= $vp;
//                $battle->mapData->specialHexesVictory->$mapHexName .= "<span class='rebelVictoryPoints'> -$vp Loyalist vp</span>";
            }
            if ($forceId == Amph::LOYALIST_FORCE) {
//                $this->victoryPoints[Amph::LOYALIST_FORCE]  += $vp;
//                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='loyalistVictoryPoints'>+$vp Loyalist vp</span>";
                $this->victoryPoints[Amph::REBEL_FORCE] -= $vp;
                $battle->mapData->specialHexesVictory->$mapHexName .= "<span class='loyalistVictoryPoints'> -$vp Rebel vp</span>";
            }
        }

    }

    public function postReinforceZones($args)
    {
        list($zones, $unit) = $args;
        if ($unit->forceId == BLUE_FORCE) {
            $zone = $unit->reinforceZone;
            $zones = [];
            if ($zone == "A") {
                foreach ($this->landingZones as $landingZone) {
                    $zones[] = new \Wargame\ReinforceZone($landingZone, "A");
                }
            }
            if ($zone == "C") {
                foreach ($this->airdropZones as $airdropZone) {
                    $zones[] = new \Wargame\ReinforceZone($airdropZone, "C");
                }
            }
        }

        return array($zones);
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
            $battle->mapData->specialHexesVictory->{$hex->name} = "<span class='loyalistVictoryPoints'>+$vp vp</span>";
        } else {
            $victorId = 1;
            $hex  = $unit->hexagon;
            $battle = Battle::getBattle();
            $battle->mapData->specialHexesVictory->{$hex->name} = "<span class='rebelVictoryPoints'>+$vp vp</span>";
            $this->victoryPoints[$victorId] += $vp;
        }
    }

    public function incrementTurn()
    {
        $battle = Battle::getBattle();

        $theUnits = $battle->force->units;
        foreach ($theUnits as $id => $unit) {

            if ($unit->status == STATUS_CAN_REINFORCE && $unit->reinforceTurn <= $battle->gameRules->turn && $unit->hexagon->parent != "deployBox") {
//                $theUnits[$id]->status = STATUS_ELIMINATED;
                $theUnits[$id]->hexagon->parent = "deployBox";
                if($unit->forceId === Amph::REBEL_FORCE){
                    if($unit->class === 'para'){
                        $unit->hexagon->parent = 'airdrop';
                    }else{
                        $unit->hexagon->parent = 'beachlanding';
                    }
                }else{
                    if($unit->reinforceZone === 'B'){
                        $unit->hexagon->parent = 'south';
                    }
                    if($unit->reinforceZone === 'D'){
                        $unit->hexagon->parent = 'west';
                    }
                    if($unit->reinforceZone === 'E'){
                        $unit->hexagon->parent = 'east';
                    }
                }
            }
        }
    }

    public function gameEnded()
    {
        $battle = Battle::getBattle();

//        $rebelOption = (int)$battle->gameRules->option;
//
//        if($rebelOption === 0){
//            if($battle->mapData->getSpecialHex($battle->specialHexA[0]) === Amph::REBEL_FORCE){
//                $this->victoryPoints[Amph::REBEL_FORCE] += 25;
//                $battle->gameRules->flashMessages[] = "Rebel Player Completes Goal";
//            }else{
//                $battle->mapData->getSpecialHex($battle->specialHexA[0]);
//                $battle->gameRules->flashMessages[] = "Rebel Player FAILS Goal";
//
//            }
//        }
//        if($rebelOption === 1){
//            if($battle->mapData->getSpecialHex($battle->specialHexB[0]) === Amph::REBEL_FORCE){
//                $this->victoryPoints[Amph::REBEL_FORCE] += 25;
//                $battle->gameRules->flashMessages[] = "Rebel Player Completes Goal";
//            }else{
//                $battle->gameRules->flashMessages[] = "Rebel Player FAILS Goal";
//
//            }
//        }
//        if($rebelOption === 2){
//            if($battle->mapData->getSpecialHex($battle->specialHexC[0]) === Amph::REBEL_FORCE){
//                $this->victoryPoints[Amph::REBEL_FORCE] += 25;
//                $battle->gameRules->flashMessages[] = "Rebel Player Completes Goal";
//            }else{
//                $battle->gameRules->flashMessages[] = "Rebel Player FAILS Goal";
//
//            }
//        }
        if ($this->victoryPoints[Amph::LOYALIST_FORCE] > $this->victoryPoints[Amph::REBEL_FORCE]) {
            $battle->gameRules->flashMessages[] = "Loyalist Player Wins";
            $this->winner = Amph::LOYALIST_FORCE;
        }
        if ($this->victoryPoints[Amph::REBEL_FORCE] > $this->victoryPoints[Amph::LOYALIST_FORCE]) {
            $battle->gameRules->flashMessages[] = "Rebel Player Wins";
            $this->winner = Amph::REBEL_FORCE;
        }
        if ($this->victoryPoints[Amph::LOYALIST_FORCE] == $this->victoryPoints[Amph::REBEL_FORCE]) {
            $battle->gameRules->flashMessages[] = "Tie Game";
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
                    if ($unit->class === "para") {
                        $this->airdropZones[] = $unit->hexagon->name;
                    } else {
                        $this->landingZones[] = $unit->hexagon->name;
                    }
                }
            }
            $symbol = new \stdClass();
            $symbol->type = 'Supply';
            $symbol->image = 'Supply.svg';
            $symbol->class = 'Rebel supply';
            $symbols = new \stdClass();
            foreach($this->airdropZones as $id){
                $symbols->$id = $symbol;
            }
            foreach($this->landingZones as $id){
                $symbols->$id = $symbol;
            }
            $battle->mapData->setMapSymbols($symbols, "supply");
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

    public function preRecoverUnits($args)
    {
        /* @var unit $unit */
//        $unit = $args[0];

        $b = Battle::getBattle();

        $goal = array_merge($this->landingZones, $this->airdropZones);
        $this->rebelGoal = $goal;

        $goal = array();
        $goal = array_merge($goal, array(110, 210, 310, 410, 510, 610, 710, 810, 910, 1010, 1110, 1210, 1310, 1410, 1510, 1610, 1710, 1810, 1910, 2010));
        $this->loyalistGoal = $goal;
    }

    function isExit($args)
    {
        list($unit) = $args;
        if ($unit->forceId == BLUE_FORCE && in_array($unit->hexagon->name, $this->landingZones)) {
            return true;
        }
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
            if ($unit->forceId == Amph::REBEL_FORCE) {
                $bias = array(5 => true, 6 => true, 1 => true);
                $goal = $this->rebelGoal;
            } else {
                $bias = array(2 => true, 3 => true, 4 => true);
                $goal = $this->loyalistGoal;
            }
            $this->unitSupplyEffects($unit, $goal, $bias, $this->supplyLen);
        }
    }

    public function preStartMovingUnit($arg)
    {
        $unit = $arg[0];
        $battle = Battle::getBattle();
        if (!empty($battle->scenario->supply) === true) {
            if ($unit->class != 'mech') {
                $battle->moveRules->enterZoc = "stop";
                $battle->moveRules->exitZoc = 0;
                $battle->moveRules->noZocZoc = false;
            } else {
                $battle->moveRules->enterZoc = 2;
                $battle->moveRules->exitZoc = 1;
                $battle->moveRules->noZocZoc = false;

            }
        }
    }

    public function playerTurnChange($arg)
    {
        $attackingId = $arg[0];
        $battle = Battle::getBattle();
        $gameRules = $battle->gameRules;

        if ($gameRules->phase == BLUE_MECH_PHASE || $gameRules->phase == RED_MECH_PHASE) {
            $gameRules->flashMessages[] = "@hide crt";
        }
        if($gameRules->turn <= $gameRules->maxTurn) {
            if ($attackingId == Amph::REBEL_FORCE) {
                $gameRules->flashMessages[] = "Rebel Player Turn";
            }
            if ($attackingId == Amph::LOYALIST_FORCE) {
                $gameRules->flashMessages[] = "Loyalist Player Turn";
            }
        }

        /*only get special VPs' at end of first Movement Phase */
    }
}