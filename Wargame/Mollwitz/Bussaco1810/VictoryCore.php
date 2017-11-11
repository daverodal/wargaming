<?php
namespace Wargame\Mollwitz\Bussaco1810;
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
    }

    public function save()
    {
        $ret = parent::save();
        return $ret;
    }

    public function postRecoverUnit($args)
    {
        parent::postRecoverUnit($args);
    }

    public function specialHexChange($args)
    {
        $battle = Battle::getBattle();
        list($mapHexName, $forceId) = $args;

        if (in_array($mapHexName, $battle->specialHexA)) {
            $this->takeHex($battle->specialHexesMap['SpecialHexA'], $forceId, $mapHexName, 5);
        }
        if (in_array($mapHexName, $battle->specialHexB)) {
            $this->takeHex($battle->specialHexesMap['SpecialHexB'], $forceId, $mapHexName, 10);
        }
    }

    public function postRecoverUnits()
    {

    }

    protected function checkVictory( $battle)
    {
        $battle = Battle::getBattle();

        $gameRules = $battle->gameRules;
        $scenario = $battle->scenario;
        $turn = $gameRules->turn;
        $frenchWin = $BritishWin = $draw = false;

        $victoryReason = "";

        if (!$this->gameOver) {
            $pData = $battle::getPlayerData($battle->scenario)['forceName'];
            $mapData = $battle->mapData;

            $AngloAlliedWinScore = 40;
            $frenchWinScore = 40;
            $AngloAlliedWin = false;
            $frenchWin = false;

            if($this->victoryPoints[Bussaco1810::FRENCH_FORCE] >= $frenchWinScore){
                $frenchWin = true;
                $victoryReason .= "Over $frenchWinScore ";
            }
            if ($this->victoryPoints[Bussaco1810::ANGLO_ALLIED_FORCE] >= $AngloAlliedWinScore) {
                $AngloAlliedWin = true;
                $victoryReason .= "Over $AngloAlliedWinScore ";
            }


            if ($frenchWin && !$AngloAlliedWin) {
                $this->winner = Bussaco1810::FRENCH_FORCE;
                $winner = $pData[$this->winner];
                $gameRules->flashMessages[] = "$winner Win";
                $gameRules->flashMessages[] = $victoryReason;
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
            if ($AngloAlliedWin && !$frenchWin) {
                $this->winner = Bussaco1810::ANGLO_ALLIED_FORCE;
                $winner = $pData[$this->winner];
                $gameRules->flashMessages[] = "$winner Win";
                $gameRules->flashMessages[] = $victoryReason;
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
            if($frenchWin && $AngloAlliedWin){
                $this->winner = 0;
                $gameRules->flashMessages[] = "Tie Game";
                $gameRules->flashMessages[] = $victoryReason;
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
            if ($turn > $gameRules->maxTurn) {
                if($this->victoryPoints[Bussaco1810::ANGLO_ALLIED_FORCE] > $this->victoryPoints[Bussaco1810::FRENCH_FORCE]){
                    $this->winner = Bussaco1810::ANGLO_ALLIED_FORCE;
                    $winner = $pData[$this->winner];
                    $gameRules->flashMessages[] = "$winner Win";
                    $gameRules->flashMessages[] = $victoryReason;
                    $gameRules->flashMessages[] = "Game Over";
                }else{
                    $this->winner = 0;
                    $gameRules->flashMessages[] = "Tie Game";
                    $gameRules->flashMessages[] = $victoryReason;
                    $gameRules->flashMessages[] = "Game Over";
                }
                $this->gameOver = true;
                return true;
            }
        }
        return false;
    }

}
