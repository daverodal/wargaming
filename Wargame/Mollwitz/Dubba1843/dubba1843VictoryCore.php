<?php
namespace Wargame\Mollwitz\Dubba1843;
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

class dubba1843VictoryCore extends \Wargame\Mollwitz\IndiaVictoryCore
{

    public function specialHexChange($args)
    {
        $battle = \Wargame\Battle::getBattle();

        list($mapHexName, $forceId) = $args;
        if ($forceId == Dubba1843::BELUCHI_FORCE) {
            $this->victoryPoints[Dubba1843::BELUCHI_FORCE]  += 15;
            $battle->mapData->specialHexesVictory->$mapHexName = "<span class='beluchi'>+15 Beluchis  vp</span>";
        }
        if ($forceId == Dubba1843::BRITISH_FORCE) {
            $this->victoryPoints[Dubba1843::BELUCHI_FORCE]  -= 15;
            $battle->mapData->specialHexesVictory->$mapHexName = "<span class='british'>-15 Beluchis  vp</span>";
        }
    }


    protected function checkVictory($battle){

        $gameRules = $battle->gameRules;
        $turn = $gameRules->turn;
        $beluchiWin =  $britishWin = false;

        if(!$this->gameOver){
            $specialHexes = $battle->mapData->specialHexes;
            if(($this->victoryPoints[Dubba1843::BRITISH_FORCE] >= 45)){
                $britishWin = true;
            }
            if(($this->victoryPoints[Dubba1843::BELUCHI_FORCE] >= 40)){
                $beluchiWin = true;
            }
            if($turn == $gameRules->maxTurn+1){
                if(!$britishWin){
                        $beluchiWin = true;
                }
            }
            if($beluchiWin && $britishWin){
                $this->winner = 0;
                $britishWin = $beluchiWin = false;
                $gameRules->flashMessages[] = "Tie Game";
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }

            if($britishWin){
                $this->winner = Dubba1843::BRITISH_FORCE;
                $gameRules->flashMessages[] = "British Win";
            }
            if($beluchiWin){
                $this->winner = Dubba1843::BELUCHI_FORCE;
                $msg = "Beluchi Win";
                $gameRules->flashMessages[] = $msg;
            }
            if($britishWin || $beluchiWin){
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
        }
        return false;
    }
}
