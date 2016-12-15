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
    function __construct($data)
    {
        parent::__construct($data);
        if ($data) {
        }
    }

    public function save()
    {
        $ret = parent::save();
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

    public function postRecoverUnits($args)
    {

    }

    public function postRecoverUnit($args)
    {
        parent::postRecoverUnit($args);
    }
}
