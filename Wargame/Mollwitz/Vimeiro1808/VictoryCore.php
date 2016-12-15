<?php
namespace Wargame\Mollwitz\Vimeiro1808;
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

        $pData = $battle::getPlayerData($battle->scenario)['forceName'];

        if (in_array($mapHexName, $battle->specialHexA)) {
            if ($forceId == $battle::FRENCH_FORCE) {
                $this->victoryPoints[$battle::FRENCH_FORCE] += 5;
                $taker = $pData[$battle::FRENCH_FORCE];
                $lTaker = strtolower($taker);
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='$lTaker'>+5 $taker vp</span>";
            }
            if ($forceId == $battle::BRITISH_FORCE) {
                $this->victoryPoints[$battle::FRENCH_FORCE] -= 5;
                $taker = $pData[$battle::BRITISH_FORCE];
                $lTaker = strtolower($taker);
                $loser = $pData[$battle::FRENCH_FORCE];
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='$lTaker'>-5 $loser vp</span>";
            }
        }
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

            $BritishWinScore = 25;
            $frenchWinScore = 25;

            if($this->victoryPoints[Vimeiro1808::FRENCH_FORCE] >= $frenchWinScore){
                $frenchWin = true;
                $victoryReason .= "Over $frenchWinScore ";
            }
            if ($this->victoryPoints[Vimeiro1808::BRITISH_FORCE] >= $BritishWinScore) {
                $BritishWin = true;
                $victoryReason .= "Over $BritishWinScore ";
            }


            if ($frenchWin && !$BritishWin) {
                $this->winner = Vimeiro1808::FRENCH_FORCE;
                $winner = $pData[$this->winner];
                $gameRules->flashMessages[] = "$winner Win";
                $gameRules->flashMessages[] = $victoryReason;
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
            if ($BritishWin && !$frenchWin) {
                $this->winner = Vimeiro1808::BRITISH_FORCE;
                $winner = $pData[$this->winner];
                $gameRules->flashMessages[] = "$winner Win";
                $gameRules->flashMessages[] = $victoryReason;
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
            if($frenchWin && $BritishWin){
                $this->winner = 0;
                $gameRules->flashMessages[] = "Tie Game";
                $gameRules->flashMessages[] = $victoryReason;
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
            if ($turn > $gameRules->maxTurn) {
                if($this->victoryPoints[Vimeiro1808::BRITISH_FORCE] > $this->victoryPoints[Vimeiro1808::FRENCH_FORCE]){
                    $this->winner = Vimeiro1808::BRITISH_FORCE;
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
