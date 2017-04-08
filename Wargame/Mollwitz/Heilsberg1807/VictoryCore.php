<?php
namespace Wargame\Mollwitz\Heilsberg1807;
use \Wargame\Battle;
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

class VictoryCore extends \Wargame\Mollwitz\victoryCore
{
    public $releaseMap;
    /*
     * zones 1-5,zero is ignored
     */
    public $releaseZones = [true, false, false, false, false, false];

    function __construct($data)
    {
        parent::__construct($data);
        if ($data) {
            $this->releaseMap = $data->victory->releaseMap;
            $this->releaseZones = $data->victory->releaseZones;
        }else{
            $this->releaseMap = new \stdClass();
        }
    }

    public function save()
    {
        $ret = parent::save();
        $ret->releaseMap = $this->releaseMap;
        $ret->releaseZones = $this->releaseZones;
        return $ret;
    }

    public function specialHexChange($args)
    {
        $battle = Battle::getBattle();
        list($mapHexName, $forceId) = $args;

        if (in_array($mapHexName, $battle->specialHexA)) {
            $this->takeHex($battle->specialHexesMap['SpecialHexA'], $forceId, $mapHexName, 10);
        }
        if (in_array($mapHexName, $battle->specialHexB)) {
            $this->takeHex($battle->specialHexesMap['SpecialHexB'], $forceId, $mapHexName, 20);
        }
        if (in_array($mapHexName, $battle->specialHexC)) {
            $this->takeHex($battle->specialHexesMap['SpecialHexC'], $forceId, $mapHexName, 20);
        }

    }

    public function phaseChange()
    {
        /* @var $battle JagCore */
        $battle = Battle::getBattle();
        /* @var $gameRules GameRules */
        $gameRules = $battle->gameRules;
        $turn = $gameRules->turn;
        $forceId = $gameRules->attackingForceId;
        $theUnits = $battle->force->units;


        if ($gameRules->phase == BLUE_MOVE_PHASE || $gameRules->phase == RED_MOVE_PHASE) {
            $gameRules->flashMessages[] = "@hide deadpile";
            if (!empty($battle->force->reinforceTurns->$turn->$forceId)) {
                $gameRules->flashMessages[] = "@show deployWrapper";
                $gameRules->flashMessages[] = "Reinforcements have been moved to the Deploy/Staging Area";
            }

            foreach ($theUnits as $id => $unit) {
                if ($unit->status == STATUS_CAN_REINFORCE && $unit->reinforceTurn <= $battle->gameRules->turn && $unit->hexagon->parent != "deployBox") {
//                $theUnits[$id]->status = STATUS_ELIMINATED;
                    $theUnits[$id]->hexagon->parent = "deployBox";
                }
            }
        }
    }

    protected function checkVictory( $battle)
    {
        $battle = Battle::getBattle();

        $gameRules = $battle->gameRules;
        $turn = $gameRules->turn;
        $frenchWin = $russianWin = $draw = false;

        $victoryReason = "";

        if (!$this->gameOver) {

            $russianWinScore = 90;
            $frenchWinScore = 90;

            $pData = $battle::getPlayerData($battle->scenario)['forceName'];

            if($this->victoryPoints[Heilsberg1807::FRENCH_FORCE] >= $frenchWinScore){
                $frenchWin = true;
                $victoryReason .= "Over $frenchWinScore ";
            }
            if ($this->victoryPoints[Heilsberg1807::RUSSIAN_FORCE] >= $russianWinScore) {
                $russianWin = true;
                $victoryReason .= "Over $russianWinScore ";
            }

            if ($frenchWin && !$russianWin) {
                $this->winner = Heilsberg1807::FRENCH_FORCE;
                $winner = $pData[$this->winner];
                $gameRules->flashMessages[] = "$winner Win";
                $gameRules->flashMessages[] = $victoryReason;
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }

            if ($russianWin && !$frenchWin) {
                $this->winner = Heilsberg1807::RUSSIAN_FORCE;
                $winner = $pData[$this->winner];
                $gameRules->flashMessages[] = "$winner Win";
                $gameRules->flashMessages[] = $victoryReason;
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }

            if($frenchWin && $russianWin){
                $gameRules->flashMessages[] = "Tie Game";
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }

            if ($turn > $gameRules->maxTurn) {
                $gameRules->flashMessages[] = "Tie Game";
                $this->winner = 0;
                $this->gameOver = true;
                return true;
            }


        }
        return false;
    }

    public function preRecoverUnits(){

        $b = Battle::getBattle();
        /* @var $force \Wargame\Force */

        if ($b->gameRules->phase == RED_MOVE_PHASE) {
            if($b->gameRules->turn == 1) {
                $force = $b->force;
                $turn = 1;
                $reinfMap = [];
                foreach (['D', 'E', 'F', 'G', 'H'] as $reinfLetter) {
                    $hexes = $b->terrain->getReinforceZonesByName($reinfLetter);
                    foreach ($hexes as $hex) {
                        $reinfMap[$hex->hexagon->name] = $turn;
                    }
                    $turn++;
                }

                $units = $force->units;
                foreach ($units as $unit) {
                    if ($unit->forceId === Heilsberg1807::RUSSIAN_FORCE) {
                        if ($unit->isOnMap()) {
                            $unitHex = $unit->hexagon->name;
                            $this->releaseMap->{$unit->id} = $reinfMap[$unit->hexagon->name];
                        }
                    }
                }
            }

        }
        for($i = 0; $i <= $b->gameRules->turn;$i++){
            $this->releaseZones[$i] = true;
        }
    }


    public function postCombatResults($arg){

        list($defenderId, $attackers, $combatResults, $dieRoll) = $arg;
        if(isset($this->releaseMap->$defenderId)){
            $zone = $this->releaseMap->$defenderId;
            if($this->releaseZones[$zone] !== true) {
                $this->releaseZones[$zone] = true;
                $b = Battle::getBattle();
                $b->gameRules->flashMessages[] = "Zone $zone units released.";
            }

        }
    }

    public function postRecoverUnit($args)
    {
        parent::postRecoverUnit($args);
        list($unit) = $args;

        $b = Battle::getBattle();
        $phase = $b->gameRules->phase;
        if($phase === RED_MOVE_PHASE || $phase === RED_COMBAT_PHASE) {
            if ($unit->isOnMap() && $unit->forceId === Heilsberg1807::RUSSIAN_FORCE) {

                $id = $unit->id;
                $zone = $this->releaseMap->$id ?? false;
                if ($zone === false) {
                    return;
                }
                if ($this->releaseZones[$zone] === false) {
                    $unit->status = STATUS_UNAVAIL_THIS_PHASE;
                }
            }
        }
    }
}
