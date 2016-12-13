<?php
namespace Wargame\Mollwitz\Hastenbeck;
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
class hastenbeckVictoryCore extends \Wargame\Mollwitz\victoryCore
{
    public function reduceUnit($args)
    {
        $unit = $args[0];
        $mult = 1;
        if($unit->class == "cavalry" || $unit->class == "artillery"){
            $mult = 2;
        }
        $this->scoreKills($unit, $mult);
    }

    public function specialHexChange($args)
    {
        $battle = \Wargame\Battle::getBattle();

        list($mapHexName, $forceId) = $args;
        if(in_array($mapHexName,$battle->specialHexA)){
            if ($forceId == Hastenbeck::ALLIED_FORCE) {
                $this->victoryPoints[Hastenbeck::ALLIED_FORCE]  += 5;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='anglo'>+5 Allied vp</span>";
            }
            if ($forceId == Hastenbeck::FRENCH_FORCE) {
                $this->victoryPoints[Hastenbeck::ALLIED_FORCE]  -= 5;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='french'>-5 Allied vp</span>";
            }
        }
        if(in_array($mapHexName,$battle->specialHexB)){
            $vp = 5;

            if ($forceId == Hastenbeck::FRENCH_FORCE) {
                $this->victoryPoints[Hastenbeck::FRENCH_FORCE]  += $vp;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='french'>+$vp French vp</span>";
            }
            if ($forceId == Hastenbeck::ALLIED_FORCE) {
                $this->victoryPoints[Hastenbeck::FRENCH_FORCE]  -= $vp;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='anglo'>-$vp French vp</span>";
            }
        }
        if(in_array($mapHexName,$battle->specialHexC)){
            $vp = 5;

            $prevForceId = $battle->mapData->specialHexes->$mapHexName;
            if ($forceId == Hastenbeck::FRENCH_FORCE) {
                $this->victoryPoints[Hastenbeck::FRENCH_FORCE]  += $vp;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='french'>+$vp French vp</span>";
                if($prevForceId !== 0) {
                    $this->victoryPoints[Hastenbeck::ALLIED_FORCE] -= $vp;
                    $battle->mapData->specialHexesVictory->$mapHexName = "<span class='anglo'>-$vp Allied vp</span>";
                }
            }
            if ($forceId == Hastenbeck::ALLIED_FORCE) {
                $this->victoryPoints[Hastenbeck::ALLIED_FORCE]  += $vp;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='anglo'>+$vp Allied vp</span>";
                if($prevForceId !== 0) {
                    $this->victoryPoints[Hastenbeck::FRENCH_FORCE] -= $vp;
                    $battle->mapData->specialHexesVictory->$mapHexName .= "<span class='french'>-$vp French vp</span>";
                }
            }
        }
    }

    protected function checkVictory($battle){
        $gameRules = $battle->gameRules;
        $turn = $gameRules->turn;
        $frenchLateWin = $frenchWin = $frenchThreeObjectives = $angloWin = false;
        $mapData = $battle->mapData;
        $objectiveHexes = array_merge($battle->specialHexB, $battle->specialHexC);

        $alliedVictoryPointsNeeded = 45;
        $frenchVictoryPointsNeeded = 60;
        $lead = 10;

        if(!$this->gameOver){
            $frenchObjectives = 0;
            foreach($objectiveHexes as $objectiveHex){
                if($mapData->getSpecialHex($objectiveHex) == Hastenbeck::FRENCH_FORCE){
                    $frenchObjectives++;
                }
            }
            if($frenchObjectives >= 3){
                $frenchThreeObjectives = true;
            }


            if($this->victoryPoints[Hastenbeck::ALLIED_FORCE] >= $alliedVictoryPointsNeeded){
                $angloWin = true;
            }
            if($frenchThreeObjectives && ($this->victoryPoints[Hastenbeck::FRENCH_FORCE] >= $frenchVictoryPointsNeeded)){
                if($turn <= 10) {
                    $frenchWin = true;
                }else{
                    $frenchLateWin = true;
                }
            }
            if($turn == $gameRules->maxTurn+1){
                if($angloWin && !$frenchWin){
                }
                if($frenchWin && !$angloWin){
                }
                if($frenchWin && $angloWin){
                    $this->winner = 0;
                    $angloWin = $frenchWin = false;
                    $gameRules->flashMessages[] = "Tie Game";
                    $gameRules->flashMessages[] = "Game Over";
                    $this->gameOver = true;
                    return true;
                }
                if(!$angloWin && !$frenchWin){
                    if(!$frenchLateWin){
                        $angloWin = true;
                        $gameRules->flashMessages[] = "French Fail to Win";
                    }else{
                        $this->winner = 0;
                        $gameRules->flashMessages[] = "Tie Game";
                        $gameRules->flashMessages[] = "Game Over";
                        $this->gameOver = true;
                        return true;
                    }

                }
            }


            if($angloWin){
                $this->winner = Hastenbeck::ALLIED_FORCE;
                $gameRules->flashMessages[] = "Allies Win";
            }
            if($frenchWin){
                $this->winner = Hastenbeck::FRENCH_FORCE;
                $msg = "French Win";
                $gameRules->flashMessages[] = $msg;
            }
            if($angloWin || $frenchWin){
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
        }
        return false;
    }
}
