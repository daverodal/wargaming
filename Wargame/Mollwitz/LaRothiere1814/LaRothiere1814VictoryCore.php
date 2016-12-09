<?php
namespace Wargame\Mollwitz\LaRothiere1814;
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

class LaRothiere1814VictoryCore extends \Wargame\Mollwitz\victoryCore
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
            if ($forceId == ALLIED_FORCE) {
                $this->victoryPoints[ALLIED_FORCE]  += 10;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='allied'>+10 Allied vp</span>";
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
        $frenchWin = $alliedWin = $draw = false;

        $victoryReason = "";

        if (!$this->gameOver) {
            $specialHexes = $battle->mapData->specialHexes;
            $winScore = 70;
            $highWinScore = 100;
            if ($this->victoryPoints[ALLIED_FORCE] >= $winScore) {
                if ($turn <= 5) {
                    $alliedWin = true;
                }
            }
            if ($this->victoryPoints[ALLIED_FORCE] >= $highWinScore) {
                $alliedWin = true;
            }



            if ($alliedWin) {
                $this->winner = ALLIED_FORCE;
                $gameRules->flashMessages[] = "Allied Win";
                $this->gameOver = true;
            }


            if ( $turn == ($gameRules->maxTurn + 1)) {
                if(!$alliedWin){
                    if($this->casualties[FRENCH_FORCE] <= $this->casualties[ALLIED_FORCE]){
                        $this->winner = FRENCH_FORCE;
                        $msg = "French Win";
                        $gameRules->flashMessages[] = $msg;
                    }else{
                        $this->winner = 0;
                        $msg = "Tie Game";
                        $gameRules->flashMessages[] = $msg;
                    }
                }
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
        }
        return false;
    }

    public function postRecoverUnits($args)
    {
        $b = Battle::getBattle();
        $scenario = $b->scenario;
        if ($b->gameRules->turn == 1 && $b->gameRules->phase == BLUE_MOVE_PHASE) {
            $b->gameRules->flashMessages[] = "Units in extreme right may not move.";
        }

        if ($b->gameRules->turn == 1 && $b->gameRules->phase == RED_MOVE_PHASE) {
                $b->gameRules->flashMessages[] = "Young Guard may not move this phase.";
        }
    }

    public function postRecoverUnit($args)
    {
        $unit = $args[0];
        $b = Battle::getBattle();
        $scenario = $b->scenario;
        $id = $unit->id;


        parent::postRecoverUnit($args);

        if ($b->gameRules->turn <= 2 && $b->gameRules->phase == BLUE_MOVE_PHASE && $unit->status == STATUS_READY && $unit->forceId == ALLIED_FORCE) {
            if($unit->reinforceZone  === "F"){
                $unit->status = STATUS_UNAVAIL_THIS_PHASE;
            }
            if($b->gameRules->turn == 1){
                if($unit->reinforceZone  === "E"){
                    $unit->status = STATUS_UNAVAIL_THIS_PHASE;
                }
            }
        }
    }
}
