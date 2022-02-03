<?php
namespace Wargame\TMCW\Airborne;
use \stdClass;
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

class airborneVictoryCore extends \Wargame\TMCW\victoryCore
{

    private $landingZones;
    private $airdropZones;


    function __construct($data)
    {
        parent::__construct($data);
        if ($data) {
            $this->landingZones = $data->victory->landingZones;
            $this->airdropZones = $data->victory->airdropZones;
        } else {
            $this->landingZones = [];
            $this->airdropZones = [];
        }
    }

    public function save()
    {
        $ret = parent::save();
        $ret->landingZones = $this->landingZones;
        $ret->airdropZones = $this->airdropZones;
        return $ret;
    }

    public function setSupplyLen($supplyLen)
    {
        $this->supplyLen = $supplyLen[0];
    }

    public function specialHexChange($args)
    {
        $battle = Battle::getBattle();

        list($mapHexName, $forceId) = $args;


        if ($forceId == Airborne::LOYALIST_FORCE) {
            $newLandings = [];
            foreach ($this->landingZones as $landingZone) {
                if ($landingZone == $mapHexName) {
                    $battle->mapData->specialHexesVictory->$mapHexName = "<span class='LoyalistVictoryPoints'>Beachhead Destroyed</span>";
                    continue;
                }
                $newLandings[] = $landingZone;
            }
            $this->landingZones = $newLandings;

            $newAirdrops = [];
            foreach ($this->airdropZones as $airdropZone) {
                if ($airdropZone == $mapHexName) {
                    $battle->mapData->specialHexesVictory->$mapHexName = "<span class='LoyalistVictoryPoints'>Airdrop zone Destroyed</span>";
                    continue;
                }
                $newAirdrops[] = $airdropZone;
            }
            $this->airdropZones = $newAirdrops;

            $battle->mapData->removeSpecialHex($mapHexName);
        }

    }

    public function postReinforceZones($args)
    {
        list($zones, $unit) = $args;
        if ($unit->forceId == BLUE_FORCE) {
            $zone = $unit->reinforceZone;
            $zones = [];

            if ($zone == "A") {
                foreach ($this->airdropZones as $airdropZone) {
                    $zones[] = new \Wargame\ReinforceZone($airdropZone, "A");
                }
            }
            if($zone == "B"){
                $zones[] = new \Wargame\ReinforceZone(101, "B");;
            }
        }

        return array($zones);
    }

    public function reduceUnit($args)
    {
        $unit = $args[0];
        if($unit->supplyUsed){
            return;
        }

        $vp = $unit->damage;

        if ($unit->forceId == 1) {
            $victorId = 2;
            $this->victoryPoints[$victorId] += $vp;
            $hex = $unit->hexagon;
            $battle = Battle::getBattle();
            $battle->mapData->specialHexesVictory->{$hex->name} = "<span class='LoyalistVictoryPoints'>+$vp vp</span>";
        } else {
            $victorId = 1;
            $hex  = $unit->hexagon;
            $battle = Battle::getBattle();
            $battle->mapData->specialHexesVictory->{$hex->name} = "<span class='RebelVictoryPoints'>+$vp vp</span>";
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
                $theUnits[$id]->hexagon->parent = $unit->reinforceZone;
            }
        }
    }

    public function gameEnded()
    {
        $battle = Battle::getBattle();
        $battle->gameRules->flashMessages[] = "Does anybody really win in war?";
        $this->winner = 0;
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
                    if ($unit->class === "para") {
                        $supply[$unit->hexagon->name] = BLUE_FORCE;
                        $this->airdropZones[] = $unit->hexagon->name;
                    }
                }
            }
            $battle->mapData->setSpecialHexes($supply);
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

        $goal = array_merge([101], $this->airdropZones);
        $this->rebelGoal = $goal;

        $goal = array();
        for($row = 1;$row <= 22;$row++){
            $goal[] = 2200+$row;
        }
        /* Don't put lower right corner in twice! */
        for($col = 1;$col <= 21;$col++){
            if($col === 13){
                continue;
            }
            $goal[] = ($col*100)+22;
        }
        $this->loyalistGoal = $goal;
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
            if ($unit->forceId == Airborne::REBEL_FORCE) {
                $bias = array(5 => true, 6 => true, 1 => true);
                $goal = $this->rebelGoal;
            } else {
                $bias = array(2 => true, 3 => true, 4 => true);
                $goal = $this->loyalistGoal;
            }
            $this->unitSupplyEffects($unit, $goal, $bias, $this->supplyLen);
        }
        if($b->gameRules->mode === COMBAT_SETUP_MODE){
            $goal = $bias = [];
            $supplyLen = 6;
            if($unit->forceId === Airborne::LOYALIST_FORCE){
                $goal = $this->loyalistGoal;
                $supplyLen = 20;
                $bias = [2 => true, 3 => true, 4 => true];
            }
            $this->unitCombatSupplyEffects($unit, $goal, $bias, $supplyLen);
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
        $supplyLen = 2;
        $bias = [];

        $this->unitCombatSupplyEffects($unit, $goal, $bias, $supplyLen);

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
        $mapData = $battle->mapData;
        $vp = $this->victoryPoints;
        $specialHexes = $mapData->specialHexes;
        $gameRules = $battle->gameRules;

        if ($gameRules->phase == BLUE_MECH_PHASE || $gameRules->phase == RED_MECH_PHASE) {
            $gameRules->flashMessages[] = "@hide crt";
        }
        if ($attackingId == Airborne::REBEL_FORCE) {
            $gameRules->flashMessages[] = "Rebel Player Turn";
            $gameRules->replacementsAvail = 1;
        }
        if ($attackingId == Airborne::LOYALIST_FORCE) {
            $gameRules->flashMessages[] = "Loyalist Player Turn";
            $gameRules->replacementsAvail = 10;
        }

        /*only get special VPs' at end of first Movement Phase */
        $this->victoryPoints = $vp;
    }
}