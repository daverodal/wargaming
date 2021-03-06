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
    public $hexesTaken = 0;
    function __construct($data)
    {

        parent::__construct($data);
        if ($data) {
            $this->hexesTaken = $data->victory->hexesTaken;
        }
    }

    public function save()
    {
        $ret = parent::save();
        $ret->hexesTaken = $this->hexesTaken;
        return $ret;
    }



    public function specialHexChange($args)
    {
        $battle = Battle::getBattle();

        list($mapHexName, $forceId) = $args;

        /*
         * make progressive values
         */
        if (in_array($mapHexName, $battle->specialHexA)) {
            $value = 5;
            if(isset($battle->scenario->progressiveVictoryHexes)){

                if($forceId === $battle->specialHexesMap['SpecialHexA']){
                    $taken = $this->hexesTaken;
                    $this->hexesTaken--;
                }else{
                    $this->hexesTaken++;
                    $taken = $this->hexesTaken;

                }
                if($this->hexesTaken < 0){
                    $this->hexesTaken = 0;
                }
                switch($taken){
                    case 0:
                        $value = 0;
                        break;
                    case 1:
                        $value = 3;
                        break;
                    case 2:
                        $value = 6;
                        break;
                    case 3:
                    case 4:
                        $value = 9;
                        break;
                    default:
                        /* should never happen */
                        $value = 9;

                }
            }
            $this->takeHex($battle->specialHexesMap['SpecialHexA'], $forceId, $mapHexName, $value);
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

            $BritishWinScore = 30;
            $frenchWinScore = 30;

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

    public function postRecoverUnit($args)
    {
        $unit = $args[0];
        $b = Battle::getBattle();
        $scenario = $b->scenario;
        $id = $unit->id;


        parent::postRecoverUnit($args);

    }
}
