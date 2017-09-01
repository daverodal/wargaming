<?php
namespace Wargame\Mollwitz\Maloyaroslavets1812;
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

    protected function checkVictory( $battle)
    {
        $battle = Battle::getBattle();

        $gameRules = $battle->gameRules;
        $scenario = $battle->scenario;
        $turn = $gameRules->turn;
        $frenchWin = $russianWin = $draw = false;

        $victoryReason = "";

        if (!$this->gameOver) {

            $pData = $battle::getPlayerData($battle->scenario)['forceName'];

            $russianWinScore = 35;
            $frenchWinScore = 35;

            if($this->victoryPoints[Maloyaroslavets1812::FRENCH_FORCE] >= $frenchWinScore){
                $allHexes = true;
                foreach($battle->specialHexA as $specialHex){
                    if($battle->mapData->getSpecialHex($specialHex) !== Maloyaroslavets1812::FRENCH_FORCE){
                        $allHexes = false;
                        break;
                    }
                }
                if($allHexes === true){
                    $frenchWin = true;
                    $victoryReason .= "Over $frenchWinScore and all hexes in Maloyaroslavets";
                }
            }
            if ($this->victoryPoints[Maloyaroslavets1812::RUSSIAN_FORCE] >= $russianWinScore) {
                $allHexes = true;
                foreach($battle->specialHexA as $specialHex){
                    if($battle->mapData->getSpecialHex($specialHex) !== Maloyaroslavets1812::RUSSIAN_FORCE){
                        $allHexes = false;
                        break;
                    }
                }
                if($allHexes === true) {
                    $russianWin = true;
                    $victoryReason .= "Over $russianWinScore and all hexes in Maloyaroslavets";
                }
            }

            if ($frenchWin && !$russianWin) {
                $this->winner = Maloyaroslavets1812::FRENCH_FORCE;
                $winner = $pData[$this->winner];
                $gameRules->flashMessages[] = "$winner Win";
                $gameRules->flashMessages[] = $victoryReason;
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
            if ($russianWin && !$frenchWin) {
                $this->winner = Maloyaroslavets1812::RUSSIAN_FORCE;
                $winner = $pData[$this->winner];
                $gameRules->flashMessages[] = "$winner Win";
                $gameRules->flashMessages[] = $victoryReason;
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
            if ($turn > $gameRules->maxTurn) {
                $gameRules->flashMessages[] = "Tie Game";
                $this->winner = 0;
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

        $unit->removeAdjustment('movement');

        if ($b->gameRules->turn <= 1 && $b->gameRules->phase == RED_MOVE_PHASE && $unit->status == STATUS_READY) {
            if(!empty($scenario->noCavMove) && $unit->class === "cavalry"){
                $unit->status = STATUS_UNAVAIL_THIS_PHASE;
            }
//            if($b->gameRules->turn === 1 && !empty($scenario->noCavMove) && $unit->class != "cavalry" && $unit->forceId === Maloyaroslavets1812::RUSSIAN_FORCE){
//                $unit->addAdjustment('movement', 'halfMovement');
//            }
        }
    }


    public function phaseChange()
    {
        parent::phaseChange();
        /* @var $battle JagCore */
        $battle = Battle::getBattle();
        /* @var $gameRules GameRules */
        $gameRules = $battle->gameRules;
        $turn = $gameRules->turn;
        $forceId = $gameRules->attackingForceId;
        $theUnits = $battle->force->units;


        if ($gameRules->phase == BLUE_MOVE_PHASE || $gameRules->phase == RED_MOVE_PHASE) {
            $gameRules->flashMessages[] = "@hide deadpile";
            if (!empty($battle->force->reinforceTurns->$turn->$forceId)) {
                $gameRules->flashMessages[] = "@show deployWrapper";
                $gameRules->flashMessages[] = "Reinforcements have been moved to the Deploy/Staging Area";
            }

            foreach ($theUnits as $id => $unit) {

                if ($unit->status == STATUS_CAN_REINFORCE && $unit->reinforceTurn <= $battle->gameRules->turn && $unit->hexagon->parent != "deployBox") {
                    $theUnits[$id]->hexagon->parent = "deployBox";
                }
            }
        }
    }






    public function postRecoverUnits()
    {
        $b = Battle::getBattle();
        $scenario = $b->scenario;

        if ($b->gameRules->turn == 1 && $b->gameRules->phase == RED_MOVE_PHASE) {
            if(!empty($scenario->noCavMove)) {
                $b->gameRules->flashMessages[] = "Russian Cavalry cannot move first turn. All other Russian units half movement.";
            }
        }
    }

}
