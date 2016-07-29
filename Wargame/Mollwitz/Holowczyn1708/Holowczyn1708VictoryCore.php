<?php
namespace Wargame\Mollwitz\Holowczyn1708;
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

class Holowczyn1708VictoryCore extends \Wargame\Mollwitz\victoryCore
{
    public $divisionReleased = false;
    public $reserveDivisionIds;


    function __construct($data)
    {
        parent::__construct($data);
        if ($data) {
            $this->divisionReleased = $data->victory->divisionReleased;
            $this->reserveDivisionIds = $data->victory->reserveDivisionIds;
        }else{
            $this->reserveDivisionIds = new \stdClass();
        }
    }

    public function save()
    {
        $ret = parent::save();
        $ret->divisionReleased = $this->divisionReleased;
        $ret->reserveDivisionIds = $this->reserveDivisionIds;

        return $ret;
    }

    public function reduceUnit($args)
    {
        $unit = $args[0];
        if($unit->class == "pontoon"){
            return;
        }
        $this->scoreKills($unit);
    }

    public function specialHexChange($args)
    {
        $battle = Battle::getBattle();

        list($mapHexName, $forceId) = $args;
        if (in_array($mapHexName, $battle->specialHexA)) {
            if ($forceId == SWEDISH_FORCE) {
                $this->victoryPoints[SWEDISH_FORCE] += 5;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='swedish'>+5 Swedish vp</span>";
            }
            if ($forceId == RUSSIAN_FORCE) {
                $this->victoryPoints[SWEDISH_FORCE] -= 5;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='saxonPolish'>-5 Swedish vp</span>";
            }
        }
        if (in_array($mapHexName, $battle->specialHexB)) {
            if ($forceId == RUSSIAN_FORCE) {
                $this->victoryPoints[RUSSIAN_FORCE] += 5;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='saxonPolish'>+5 Russian vp</span>";
            }
            if ($forceId == SWEDISH_FORCE) {
                $this->victoryPoints[RUSSIAN_FORCE] -= 5;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='swedish'>-5 Russian vp</span>";
            }
        }
    }

    protected function checkVictory( $battle)
    {
        $gameRules = $battle->gameRules;
        $scenario = $battle->scenario;
        $turn = $gameRules->turn;
        $swedishWin = $saxonPolishWin = $draw = false;

        if (!$this->gameOver) {
            $specialHexes = $battle->mapData->specialHexes;
            $earlyWinScore = 30;
            $winScore = 40;
            if ($this->victoryPoints[SWEDISH_FORCE] >= $earlyWinScore) {
                if ($turn <= 6) {
                    $swedishWin = true;
                }
            }
            if ($this->victoryPoints[SWEDISH_FORCE] >= $winScore) {
                    $swedishWin = true;

            }
            if ($this->victoryPoints[RUSSIAN_FORCE] >= $winScore) {
                $saxonPolishWin = true;
            }

            if ($swedishWin && !$saxonPolishWin) {
                $this->winner = SWEDISH_FORCE;
                $gameRules->flashMessages[] = "Swedish Win";
                $this->gameOver = true;
            }
            if ($saxonPolishWin && !$swedishWin) {
                $this->winner = RUSSIAN_FORCE;
                $gameRules->flashMessages[] = "Russian Win";
                $this->gameOver = true;
            }
            if($swedishWin && $saxonPolishWin){
                $gameRules->flashMessages[] = "Tie Game";
                $this->gameOver = true;
            }
            if ($turn == ($gameRules->maxTurn + 1)) {
                $this->gameOver = true;
                if(!$saxonPolishWin && !$swedishWin){
                    $this->winner = RUSSIAN_FORCE;
                    $gameRules->flashMessages[] = "Russian Win";
                    $gameRules->flashMessage[] = "Swedish Fail to Win";
                }
            }
            if($this->gameOver){
                $gameRules->flashMessages[] = "Game Over";
                return true;
            }
        }
        return false;
    }

    public function phaseChange()
    {

        /* @var $battle MartianCivilWar */
        $battle = Battle::getBattle();
        /* @var $gameRules GameRules */
        $gameRules = $battle->gameRules;
        $turn = $gameRules->turn;
        $force = $battle->force;

        if ($turn == 1 && $gameRules->phase == BLUE_MOVE_PHASE) {
            $supply = [];
            $units = $force->units;
            $num = count($units);
            for ($i = 0; $i < $num; $i++) {
                $unit = $units[$i];
                if ($unit->class == "pontoon" && $unit->hexagon->parent === "gameImages") {
                    $supply[$unit->hexagon->name] = 0 - $unit->forceId;
                    $battle->pontoons[] = $unit->hexagon->name;
                    $force->eliminateUnit($i);
                }
            }
            $battle->mapData->setSpecialHexes($supply);

        }
        $autoRelease = !empty($battle->scenario->autoRelease);
        $neverReleased = !empty($battle->secnario->neverReleased);
        if (!$neverReleased && $turn >= 2 && $gameRules->phase == RED_MOVE_PHASE && $this->divisionReleased === false){
            if($autoRelease){
                $dieRoll = '';
                if($turn >= 3){
                    $this->divisionReleased = true;
                }
            }else{
                $dieRoll = rand(1,6);
                if($dieRoll === 6 || $autoRelease === true){
                    $this->divisionReleased = true;
                }
            }

            if($this->divisionReleased === false){
                $battle->gameRules->flashMessages[] = "Sheremetiev’s Division Not Released $dieRoll";
            }else{
                $battle->gameRules->flashMessages[] = "Sheremetiev’s Division Released!!!!!!! $dieRoll";
            }

        }
    }

    public function postRecoverUnits($args)
    {
        $b = Battle::getBattle();
        if ($b->gameRules->turn == 1 && $b->gameRules->phase == BLUE_MOVE_PHASE) {
            $b->gameRules->flashMessages[] = "Swedish Movement allowance +1 this turn.";
        }
        if ($b->gameRules->turn == 1 && $b->gameRules->phase == RED_MOVE_PHASE) {
            $b->gameRules->flashMessages[] = "Russian Movement halved this turn.";
        }
    }

    public function postCombatResults($arg){

        list($defenderId, $attackers, $combatResults, $dieRoll) = $arg;
        if($this->divisionReleased === false && isset($this->reserveDivisionIds->$defenderId)){
            $this->divisionReleased = true;
            $b = Battle::getBattle();

            $b->gameRules->flashMessages[] = "Reserve Division Released.";

        }
    }

    public function postRecoverUnit($args)
    {
        $unit = $args[0];
        $b = Battle::getBattle();
        $id = $unit->id;

        if($b->gameRules->turn === 1 && $b->gameRules->phase === BLUE_MOVE_PHASE){
            $reserveHexes = [];
            $dZones = $b->terrain->getReinforceZonesByName('D');
            foreach($dZones as $dZone){
                $reserveHexes[$dZone->hexagon->name] = true;
            }
            /* this will get turned into an object */
            if(isset($reserveHexes[$unit->hexagon->name])){
                $this->reserveDivisionIds->$id = true;
            }
        }

        if($this->divisionReleased === false && $unit->status == STATUS_READY && isset($this->reserveDivisionIds->$id)){
            $unit->status = STATUS_UNAVAIL_THIS_PHASE;
        }

        parent::postRecoverUnit($args);
        if ($b->gameRules->turn == 1 && $b->gameRules->phase == BLUE_MOVE_PHASE && $unit->status == STATUS_READY) {
            $this->movementCache->$id = $unit->maxMove;
            $unit->maxMove = $unit->maxMove+1;
        }
        if ($b->gameRules->turn == 1 && $b->gameRules->phase == BLUE_COMBAT_PHASE && isset($this->movementCache->$id)) {
            $unit->maxMove = $this->movementCache->$id;
            unset($this->movementCache->$id);
        }
        if ($b->gameRules->turn == 1 && $b->gameRules->phase == RED_MOVE_PHASE && $unit->status == STATUS_READY) {
            $this->movementCache->$id = $unit->maxMove;
            $unit->maxMove = floor($unit->maxMove/2);
        }
        if ($b->gameRules->turn == 1 && $b->gameRules->phase == RED_COMBAT_PHASE && isset($this->movementCache->$id)) {
            $unit->maxMove = $this->movementCache->$id;
            unset($this->movementCache->$id);
        }
    }
}
