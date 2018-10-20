<?php
namespace Wargame\Mollwitz\Speyerbach1703;
use Wargame\Mollwitz\HorseMusket\HorseMusket;
use Wargame\Battle;
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
        $draw = false;

        $victoryReason = "";

        if (!$this->gameOver) {
            $pData = $battle::getPlayerData($battle->scenario)['forceName'];
            $mapData = $battle->mapData;

            $playerTwoWinScore = 32;
            $playerOneWinScore = 22;
            if(!empty($scenario->ironman)){
                $playerOneWinScore = 22;
            }
            $playerTwoWin = false;
            $playerOneWin = false;

            if($this->victoryPoints[HorseMusket::PLAYER_ONE] >= $playerOneWinScore){
                $playerOneWin = true;
                $victoryReason .= "Over $playerOneWinScore ";
            }
            if ($this->victoryPoints[HorseMusket::PLAYER_TWO] >= $playerTwoWinScore) {
                $playerTwoWin = true;
                $victoryReason .= "Over $playerTwoWinScore ";
            }


            if ($playerOneWin && !$playerTwoWin) {
                $this->winner = HorseMusket::PLAYER_ONE;
                $winner = $pData[$this->winner];
                $gameRules->flashMessages[] = "$winner Win";
                $gameRules->flashMessages[] = $victoryReason;
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
            if ($playerTwoWin && !$playerOneWin) {
                $this->winner = HorseMusket::PLAYER_TWO;
                $winner = $pData[$this->winner];
                $gameRules->flashMessages[] = "$winner Win";
                $gameRules->flashMessages[] = $victoryReason;
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
            if($playerOneWin && $playerTwoWin){
                $this->winner = 0;
                $gameRules->flashMessages[] = "Tie Game";
                $gameRules->flashMessages[] = $victoryReason;
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
            if ($turn > $gameRules->maxTurn) {
                $this->winner = 0;
                $gameRules->flashMessages[] = "Tie Game";
                $gameRules->flashMessages[] = $victoryReason;
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
        }
        return false;
    }
}
