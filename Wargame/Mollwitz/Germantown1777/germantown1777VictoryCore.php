<?php
namespace Wargame\Mollwitz\Germantown1777;
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

class germantown1777VictoryCore extends \Wargame\Mollwitz\victoryCore
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

    public function reduceUnit($args)
    {
        $unit = $args[0];
        $this->scoreKills($unit);
    }

    public function specialHexChange($args)
    {
        $battle = Battle::getBattle();

        list($mapHexName, $forceId) = $args;
        if (in_array($mapHexName, $battle->specialHexA)) {
            if ($forceId == Germantown1777::REBEL_FORCE) {
                $this->victoryPoints[Germantown1777::REBEL_FORCE] += 20;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='rebel'>+20 Rebel vp</span>";
            }
            if ($forceId == Germantown1777::LOYALIST_FORCE) {
                $this->victoryPoints[Germantown1777::REBEL_FORCE] -= 20;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='loyalist'>-20 Rebel vp</span>";
            }
        }
    }

    protected function checkVictory( $battle)
    {
        $gameRules = $battle->gameRules;
        $scenario = $battle->scenario;
        $turn = $gameRules->turn;
        $rebelWin = $loyalistWin = $draw = false;

        if (!$this->gameOver) {
            $winScore = 20;

            if ($this->victoryPoints[Germantown1777::REBEL_FORCE] >= $winScore) {
                    $rebelWin = true;

            }
            if ($this->victoryPoints[Germantown1777::LOYALIST_FORCE] >= $winScore) {
                $loyalistWin = true;
            }

            if ($rebelWin && !$loyalistWin) {
                $this->winner = Germantown1777::REBEL_FORCE;
                $gameRules->flashMessages[] = "Rebel Win";
                $this->gameOver = true;
            }
            if ($loyalistWin && !$rebelWin) {
                $this->winner = Germantown1777::LOYALIST_FORCE;
                $gameRules->flashMessages[] = "Loyalist Win";
                $this->gameOver = true;
            }
            if($rebelWin && $loyalistWin){
                if($this->victoryPoints[Germantown1777::REBEL_FORCE] > $this->victoryPoints[Germantown1777::LOYALIST_FORCE]){
                    $gameRules->flashMessages[] = "Rebel Win";
                    $gameRules->flashMessages[] = "Rebels have more point.";
                    $this->winner = Germantown1777::REBEL_FORCE;
                }else{
                    $gameRules->flashMessages[] = "Tie Game";
                }
                $this->gameOver = true;
            }
            if ($turn == ($gameRules->maxTurn + 1)) {
                $this->gameOver = true;
                if(!$loyalistWin && !$rebelWin){
                    $gameRules->flashMessages[] = "Tie Game";
                }
            }
            if($this->gameOver){
                $gameRules->flashMessages[] = "Game Over";
                return true;
            }
        }
        return false;
    }



    public function preCombatResults($args){
        list($defenderId, $attackers, $combatResults, $dieRoll) = $args;
        $b = Battle::getBattle();
        if($b->force->units[$defenderId]->maxMove  === 0){
            if($combatResults === DR){
                $combatResults = NE;
            }
        }
        return [$defenderId, $attackers, $combatResults, $dieRoll];

    }

}
