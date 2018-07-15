<?php
namespace Wargame\NTA;
use Wargame\Battle;
/**
 *
 * Copyright 2012-2015 David Rodal
 * User: David Markarian Rodal
 * Date: 3/8/15
 * Time: 5:48 PM
 *
 *  This program is free software; you can redistribute it
 *  and/or modify it under the terms of the GNU General Public License
 *  as published by the Free Software Foundation;
 *  either version 2 of the License, or (at your option) any later version
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
/**
 * Created by JetBrains PhpStorm.
 * User: markarianr
 * Date: 5/7/13
 * Time: 7:06 PM
 * To change this template use File | Settings | File Templates.
 */

class victoryCore extends \Wargame\VictoryCore
{
    public $victoryPoints;

    function __construct($data)
    {
        parent::__construct($data);
        if($data) {
            $this->victoryPoints = $data->victory->victoryPoints;
        } else {
            $this->victoryPoints = array(0, 0, 0);
        }
    }

    public function save()
    {
        $ret = parent::save();
        $ret->victoryPoints = $this->victoryPoints;
        return $ret;
    }

    public function isUnitProhibitedFromAdvancing($args){
        list($unit) = $args;
        if($unit->class === 'artillery' || $unit->class === 'horseartillery'){
            return true;
        }
        return false;
    }

    public function phaseChange()
    {
    }

    public function playerTurnChange($arg){
        $attackingId = $arg[0];
        $battle = Battle::getBattle();

        /* @var GameRules $gameRules */
        $gameRules = $battle->gameRules;
        $turn = $gameRules->turn;
        if($gameRules->phase == BLUE_MOVE_PHASE || $gameRules->phase ==  RED_MOVE_PHASE){
            $gameRules->flashMessages[] = "@hide crt";
        }

        if ($turn > $gameRules->maxTurn){
            return;
        }
        if($attackingId == BLUE_FORCE){
            $gameRules->flashMessages[] = "Red Player Turn";
        }
        if($attackingId  == RED_FORCE){
            $gameRules->flashMessages[] = "Blue Player Turn";
        }
    }

    public function gameEnded(){

        $battle = Battle::getBattle();

        $ownerObj = $battle->mapData->specialHexes;
        foreach($ownerObj as $owner){
            break;
        }
        if($owner == 0){
            $name = "Nobody Wins";
        }
        if($owner == 1){
            $name = "<span class='rebelFace'>Red Wins </span>";
        }
        if($owner == 2){
            $name = "<span class='loyalistFace'>Blue Wins </span>";
        }
        $battle->gameRules->flashMessages[] = $name;
        $this->gameOver = true;
    }
    public function postRecoverUnit($args)
    {
        return $args;
        $unit = $args[0];
        if($unit->forceId == 1) {
            return;
        }
        $b = Battle::getBattle();
        $id = $unit->id;
    }
}