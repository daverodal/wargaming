<?php
namespace Wargame\Mollwitz\Fontenoy1745;
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

class fontenoy1745VictoryCore extends \Wargame\Mollwitz\victoryCore
{

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
            if ($forceId == FRENCH_FORCE) {
                $this->victoryPoints[FRENCH_FORCE]  += 10;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='french'>+10 French vp</span>";
            }
            if ($forceId == ALLIED_FORCE) {
                $this->victoryPoints[FRENCH_FORCE]  -= 10;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='allies'>-10 French vp</span>";
            }
        }

        if (in_array($mapHexName, $battle->specialHexB)) {
            if ($forceId == ALLIED_FORCE) {
                $this->victoryPoints[ALLIED_FORCE]  += 10;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='allies'>+10 Allied vp</span>";
            }
            if ($forceId == FRENCH_FORCE) {
                $this->victoryPoints[ALLIED_FORCE]  -= 10;
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
        $frenchWin = $alliesWin = $draw = false;

        $victoryReason = "";

        if (!$this->gameOver) {
            $specialHexes = $battle->mapData->specialHexes;
            $winScore = 50;

            if($this->victoryPoints[FRENCH_FORCE] >= $winScore){
                $frenchWin = true;
                $victoryReason .= "Over $winScore ";
            }
            if ($this->victoryPoints[ALLIED_FORCE] >= $winScore) {
                $alliesWin = true;
                $victoryReason .= "Over $winScore ";
            }

            if ($frenchWin && !$alliesWin) {
                $this->winner = FRENCH_FORCE;
                $gameRules->flashMessages[] = "French Win";
                $gameRules->flashMessages[] = $victoryReason;
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
            if ($alliesWin && !$frenchWin) {
                $this->winner = ALLIED_FORCE;
                $gameRules->flashMessages[] = "Allies Win";
                $gameRules->flashMessages[] = $victoryReason;
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
            if($frenchWin && $alliesWin){
                $gameRules->flashMessages[] = "Tie Game";
                $gameRules->flashMessages[] = $victoryReason;
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
            if ($turn > $gameRules->maxTurn) {
                if($this->victoryPoints[FRENCH_FORCE] >= 25){
                    $this->winner = ALLIED_FORCE;
                    $gameRules->flashMessages[] = "French Win";
                    $gameRules->flashMessages[] = "Allies Fail to Win";
                }else{
                    $gameRules->flashMessages[] = "Tie Game";
                    $gameRules->flashMessages[] = "Game Over";
                }

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
