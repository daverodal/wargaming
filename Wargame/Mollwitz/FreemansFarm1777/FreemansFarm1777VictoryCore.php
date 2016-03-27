<?php
/*
Copyright 2012-2015 David Rodal

This program is free software; you can redistribute it
and/or modify it under the terms of the GNU General Public License
as published by the Free Software Foundation;
either version 2 of the License, or (at your option) any later version

This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

You should have received a copy of the GNU General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.
   */
/**
 * Created by JetBrains PhpStorm.
 * User: markarianr
 * Date: 5/7/13
 * Time: 7:06 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Wargame\Mollwitz\FreemansFarm1777;
use \Wargame\Battle;
class FreemansFarm1777VictoryCore extends \Wargame\Mollwitz\victoryCore
{
    public $loyalVictoryHex = false;
    public $rebelVictoryHex = false;

    function __construct($data)
    {
        parent::__construct($data);
        if ($data) {
            $this->loyalVictoryHex = $data->victory->loyalVictoryHex;
            $this->rebelVictoryHex = $data->victory->rebelVictoryHex;
        }
    }

    public function save()
    {
        $ret = parent::save();
        $ret->loyalVictoryHex = $this->loyalVictoryHex;
        $ret->rebelVictoryHex = $this->rebelVictoryHex;
        return $ret;
    }

    public function reduceUnit($args)
    {
        $unit = $args[0];
        $mult = 1;
        $this->scoreKills($unit, $mult);
    }

    public function specialHexChange($args)
    {
        $battle = Battle::getBattle();

        list($mapHexName, $forceId) = $args;

        if (in_array($mapHexName, $battle->specialHexA)) {

            if ($forceId == REBEL_FORCE) {
                $this->rebelVictoryHex = true;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='rebel'>+10 Rebel vp</span>";
            }
            if ($forceId == LOYALIST_FORCE) {
                $this->rebelVictoryHex = false;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='loyalist'>-10 Rebel vp</span>";
            }
        }

        if (in_array($mapHexName, $battle->specialHexB)) {

            if ($forceId == LOYALIST_FORCE) {
                $this->loyalVictoryHex = true;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='loyalist'>+20 Loyalist vp</span>";
            }
            if ($forceId == REBEL_FORCE) {
                $this->loyalVictoryHex = false;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='loyalist'>-20 Loyalist vp</span>";
            }
        }

    }

    protected function checkVictory( $battle)
    {
        $battle = Battle::getBattle();

        $gameRules = $battle->gameRules;
        $scenario = $battle->scenario;
        $turn = $gameRules->turn;
        $loyalWin = $rebelWin = $draw = false;

        $victoryReason = "";

        if (!$this->gameOver) {
            $loyalScore = 16;
            $rebelScore = 16;

            if ($turn > $gameRules->maxTurn) {
                if($this->loyalVictoryHex){
                    $this->victoryPoints[LOYALIST_FORCE] += 20;
                }
                if($this->rebelVictoryHex){
                    $this->victoryPoints[REBEL_FORCE] += 10;
                }
            }

            if ($this->victoryPoints[LOYALIST_FORCE] >= $loyalScore) {
                $loyalWin = true;
                $victoryReason .= "$loyalScore or over";
            }

            if ($this->victoryPoints[REBEL_FORCE] >= $rebelScore) {
                $rebelWin = true;
                $victoryReason .= "$rebelScore or over ";
            }

            if ($rebelWin && !$loyalWin) {
                $this->winner = REBEL_FORCE;
                $gameRules->flashMessages[] = "Rebel Win";
                $gameRules->flashMessages[] = $victoryReason;
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
            if ($loyalWin && !$rebelWin) {
                $this->winner = LOYALIST_FORCE;
                $gameRules->flashMessages[] = "Loyal Win";
                $gameRules->flashMessages[] = $victoryReason;
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
            if ($loyalWin && $rebelWin) {
                $gameRules->flashMessages[] = "Tie Game";
                $gameRules->flashMessages[] = $victoryReason;
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
            if ($turn > $gameRules->maxTurn) {
                $gameRules->flashMessages[] = "Tie Game";
                $gameRules->flashMessages[] = "Nobody over 16";
                $gameRules->flashMessages[] = "Game Over";
                return true;
            }

            }
        return false;
    }

    public function preRecoverUnits()
    {
        parent::preRecoverUnits();

    }



    public function postRecoverUnit($args)
    {
        $unit = $args[0];
        $b = Battle::getBattle();
        $scenario = $b->scenario;
        $id = $unit->id;

        if($b->gameRules->turn <= 2 && !isset($this->southOfTheRiver)){
            $terrain = $b->terrain;
            $reinforceZones = $terrain->reinforceZones;
            $southOfTheRiver = [];
            foreach($reinforceZones as $reinforceZone){
                if($reinforceZone->name == 'D'){
                    $southOfTheRiver[$reinforceZone->hexagon->name] = true;
                }
            }
            $this->southOfTheRiver = $southOfTheRiver;
        }

        parent::postRecoverUnit($args);

        if ($b->gameRules->turn == 1 && $b->gameRules->phase == BLUE_MOVE_PHASE && $unit->status == STATUS_READY && $unit->forceId == LOYALIST_FORCE) {
            /* if early Movement set and unit is north of river they can move */
                $unit->status = STATUS_UNAVAIL_THIS_PHASE;
        }

        if ($b->gameRules->turn <= 3 && $b->gameRules->phase == BLUE_MOVE_PHASE && $unit->status == STATUS_READY
            && $unit->hexagon->number >= 2413 &&  $unit->hexagon->number <= 2417 &&  $unit->forceId == LOYALIST_FORCE){
                $unit->status = STATUS_UNAVAIL_THIS_PHASE;
        }
    }
}
