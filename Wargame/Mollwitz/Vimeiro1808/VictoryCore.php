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
            $mapData = $battle->mapData;
            if ($turn > $gameRules->maxTurn) {
                list($hexB) = $battle->specialHexB;
                if ($mapData->getSpecialHex($hexB) == Vimeiro1808::RUSSIAN_FORCE) {
                    $this->winner = Vimeiro1808::RUSSIAN_FORCE;
                    $gameRules->flashMessages[] = "Russians Control Bridge and WIN";
                    $this->gameOver = true;
                    return true;
                }
            }
            $AlliedWinScore = 35;
            $frenchWinScore = 35;

            if($this->victoryPoints[Vimeiro1808::FRENCH_FORCE] >= $frenchWinScore){
                $frenchWin = true;
                $victoryReason .= "Over $frenchWinScore ";
            }
            if ($this->victoryPoints[Vimeiro1808::RUSSIAN_FORCE] >= $AlliedWinScore) {
                $AlliedWin = true;
                $victoryReason .= "Over $AlliedWinScore ";
            }


            if ($frenchWin && !$AlliedWin) {
                $this->winner = Vimeiro1808::FRENCH_FORCE;
                $gameRules->flashMessages[] = "French Win";
                $gameRules->flashMessages[] = $victoryReason;
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
            if ($AlliedWin && !$frenchWin) {
                $this->winner = Vimeiro1808::RUSSIAN_FORCE;
                $gameRules->flashMessages[] = "Russian Win";
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

                $vHexes = [0,0,0];

                /* French control bridge hex */
                foreach($battle->specialHexA as $hexA){
                    $vHexes[$mapData->getSpecialHex($hexA)]++;
                }
                if($vHexes[Vimeiro1808::FRENCH_FORCE] < $vHexes[Vimeiro1808::RUSSIAN_FORCE]){
                    $gameRules->flashMessages[] = "Russians Control More Hexes and WIN";
                    $gameRules->flashMessages[] = $vHexes[Vimeiro1808::RUSSIAN_FORCE]." vs ".$vHexes[Vimeiro1808::FRENCH_FORCE];
                    $this->winner = Vimeiro1808::RUSSIAN_FORCE;
                }elseif($vHexes[Vimeiro1808::FRENCH_FORCE] > $vHexes[Vimeiro1808::RUSSIAN_FORCE]){
                    $gameRules->flashMessages[] = "French Control More Hexes and WIN";
                    $gameRules->flashMessages[] = $vHexes[Vimeiro1808::FRENCH_FORCE]." vs ".$vHexes[Vimeiro1808::RUSSIAN_FORCE];

                    $this->winner = Vimeiro1808::FRENCH_FORCE;
                }else{
                    $gameRules->flashMessages[] = "TIE GAME";
                    $this->winner = 0;
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
