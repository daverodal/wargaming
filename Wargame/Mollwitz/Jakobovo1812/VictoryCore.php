<?php
namespace Wargame\Mollwitz\Jakobovo1812;
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
    public $deadGuardInf;
    function __construct($data)
    {
        parent::__construct($data);
        if ($data) {
            $this->deadGuardInf = $data->victory->deadGuardInf;
        }
    }

    public function save()
    {
        $ret = parent::save();
        $ret->deadGuardInf = $this->deadGuardInf;
        return $ret;
    }

    public function specialHexChange($args)
    {
        $battle = Battle::getBattle();

        list($mapHexName, $forceId) = $args;
        if (in_array($mapHexName, $battle->specialHexA)) {
            if ($forceId == RUSSIAN_FORCE) {
                $this->victoryPoints[RUSSIAN_FORCE]  += 10;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='allied'>+10 Allied vp</span>";
            }
            if ($forceId == FRENCH_FORCE) {
                $this->victoryPoints[RUSSIAN_FORCE]  -= 10;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='french'>-10 Allied vp</span>";
            }
        }

    }

    protected function checkVictory( $battle)
    {
        $battle = Battle::getBattle();

        $gameRules = $battle->gameRules;
        $scenario = $battle->scenario;
        $turn = $gameRules->turn;
        $frenchWin = $AlliedWin = $draw = false;

        $victoryReason = "";

        if (!$this->gameOver) {
            $specialHexes = $battle->mapData->specialHexes;
            $AlliedWinScore = 30;
            $frenchWinScore = 40;

            if($this->victoryPoints[FRENCH_FORCE] >= $frenchWinScore){
                $frenchWin = true;
                $victoryReason .= "Over $frenchWinScore ";
            }
            if ($this->victoryPoints[RUSSIAN_FORCE] >= $AlliedWinScore) {
                $AlliedWin = true;
                $victoryReason .= "Over $AlliedWinScore ";
            }

            if ($frenchWin && !$AlliedWin) {
                $this->winner = FRENCH_FORCE;
                $gameRules->flashMessages[] = "French Win";
                $gameRules->flashMessages[] = $victoryReason;
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
            if ($AlliedWin && !$frenchWin) {
                $this->winner = RUSSIAN_FORCE;
                $gameRules->flashMessages[] = "Allies Win";
                $gameRules->flashMessages[] = $victoryReason;
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
            if($frenchWin && $AlliedWin){
                $gameRules->flashMessages[] = "Tie Game";
                $gameRules->flashMessages[] = $victoryReason;
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
            if ($turn > $gameRules->maxTurn) {
                $this->winner = RUSSIAN_FORCE;
                $gameRules->flashMessages[] = "Allies Win";
                $gameRules->flashMessages[] = "French Fail to Win";
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
        $unit = $args[0];
        $b = Battle::getBattle();
        $scenario = $b->scenario;
        $id = $unit->id;


        parent::postRecoverUnit($args);

    }
}
