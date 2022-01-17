<?php
namespace Wargame\TMCW\TinCans;
use \Wargame\Battle;
use \stdClass;
use Wargame\TMCW\Airborne\Airborne;

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

    protected function checkVictory($attackingId, $battle){
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
        /* for now */
        return false;
        $this->gameOver = true;
        return true;
    }

    public function phaseChange()
    {

        /* @var $battle MartianCivilWar */
        $battle = Battle::getBattle();
        $b = $battle;
        /* @var $gameRules GameRules */
        $gameRules = $battle->gameRules;
        $forceId = $gameRules->attackingForceId;
        $turn = $gameRules->turn;
        $force = $battle->force;
        if ($b->gameRules->turn == 1 && $b->gameRules->phase == RED_MOVE_PHASE) {
            $gameRules->flashMessages[] = "Stalin orders no retreat. All Sovets in zoc remain there";
        }
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

        if ($unit->forceId != $b->gameRules->attackingForceId) {
//            return;
        }
    }

    public function playerTurnChange($arg)
    {
        parent::playerTurnChange($arg);
    }
}