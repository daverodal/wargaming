<?php
namespace Wargame\ModernBattles\Bulge;
use Wargame\Additional\EastWest\EastWest;
use \Wargame\Battle;
use \stdClass;
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

class VictoryCore extends \Wargame\TMCW\victoryCore
{

    public $armorReleased;

    function __construct($data)
    {
        parent::__construct($data);
        if ($data) {
            $this->armorReleased = $data->victory->armorReleased ?? false;

        } else {
            $this->victoryPoints[3] = 0;
            $this->armorReleased = false;
        }
    }

    public function vetoPhaseChange(){

        return false;
    }

    public function save()
    {
        $ret = parent::save();
        $ret->armorReleased = $this->armorReleased;
        return $ret;
    }

    public function reduceUnit($args)
    {
        /* @var $unit SimpleUnit */
        $unit = $args[0];

        $vp = $unit->damage;
        $hex  = $unit->hexagon;
        $unit->reinforceZone = "C";
        if ($unit->forceId == Bulge::SOVIET_FORCE) {
            $victorId = Bulge::NATO_FORCE;

            $this->victoryPoints[$victorId] += $vp;
            $hex = $unit->hexagon;
            $battle = Battle::getBattle();
            if(empty($battle->mapData->specialHexesVictory->{$hex->name})){
                $battle->mapData->specialHexesVictory->{$hex->name} = '';
            }
            $battle->mapData->specialHexesVictory->{$hex->name} .= "<span class='northernVictoryPoints'>+$vp</span>";
        } else {
            $victorId = Bulge::SOVIET_FORCE;

            $hex  = $unit->hexagon;
            $battle = Battle::getBattle();
            if(empty($battle->mapData->specialHexesVictory->{$hex->name})){
                $battle->mapData->specialHexesVictory->{$hex->name} = '';
            }
            $battle->mapData->specialHexesVictory->{$hex->name} .= "<span class='southernVictoryPoints'>+$vp</span>";
            $this->victoryPoints[$victorId] += $vp;
        }
    }


    protected function checkVictory($attackingId, $battle){

        return false;
    }

    public function incrementTurn()
    {


    }

    public function gameEnded()
    {
        $battle = Battle::getBattle();
//        $kiev = $battle->specialHexC[0];
//        if ($battle->mapData->getSpecialHex($kiev) === NorthVsSouth::NATO_FORCE) {
//            $battle->gameRules->flashMessages[] = "Soviet Player Wins";
//        }else{
//            $battle->gameRules->flashMessages[] = "German Player Wins";
//        }
        $battle->gameRules->flashMessages[] = "Nobody Wins";

        $this->gameOver = true;
        return true;
    }
    public function preStartMovingUnit($arg)
    {
        $unit = $arg[0];
        $battle = Battle::getBattle();
            $battle->moveRules->enterZoc = "stop";
            $battle->moveRules->exitZoc = 0;
            $battle->moveRules->noZocZoc = false;
    }

    public function preRecoverUnit($args)
    {
        /* @var unit $unit */
        $unit = $args[0];

        if($unit->status === STATUS_REPLACED){
            $unit->status = STATUS_CAN_REINFORCE;
        }
        /*
         * all units move in second movement phase
         */
    }
    public function postRecoverUnit($args)
    {
        /* @var unit $unit */
        $unit = $args[0];

//        if($unit->class === "airpower"){
//            $battle = Battle::getBattle();
//            $gameRules = $battle->gameRules;
//            if($gameRules->mode === COMBAT_SETUP_MODE){
//                $unit->status = STATUS_READY;
//            }else{
//                $unit->status = STATUS_UNAVAIL_THIS_PHASE;
//            }
//        }

        if($unit->status === STATUS_REPLACED){
            $unit->status = STATUS_CAN_REINFORCE;
        }
        /*
         * all units move in second movement phase
         */
    }

//    public function postReinforceZoneNames($args)
//    {
//        $battle = Battle::getBattle();
//        /* @var MapData $mapData */
//        $mapData = $battle->mapData;
//        $specialHexes = $battle->specialHexB;
//
//
//        list($zoneNames, $unit, $hexagon) = $args;
//        $zones = [];
//
//        if ($unit->nationality === "southern") {
//            foreach ($specialHexes as $specialHex) {
//                if ($mapData->getSpecialHex($specialHex) == Bulge::SOVIET_FORCE) {
//
//                }
//            }
//        }
//
//        if ($unit->nationality === "northern") {
//            $specialHexes = $battle->specialHexA;
//            foreach ($specialHexes as $specialHex) if ($mapData->getSpecialHex($specialHex) == Bulge::NATO_FORCE) {
//                    if($specialHex == $hexagon->getNumber()){
//                        $zones[] = $specialHex;
//                    }
//                    $mapHex = $mapData->getHex($specialHex);
//                    $neighbors = $mapHex->neighbors;
//                    foreach($neighbors as $neighbor){
//                        $newHexNum = $neighbor;
//                        if(is_object($neighbor)){
//                            $newHexNum = $neighbor->hexNum;
//                        }
//                        if ($battle->terrain->terrainIsHex($newHexNum, "blocked")) {
//                            continue;
//                        }
//                        if($newHexNum == $hexagon->getNumber()){
//                            $zones[] = $unit->reinforceZone;
//                        }
//                    }
//                }
//            }
//           return [$zones];
//    }

    public function isDeterminedAble($args){
         return true;
    }



    public function postCombatResults($args){
        list($defenderId, $attackers, $combatResults, $dieRoll) = $args;
        $b = Battle::getBattle();
        foreach ($attackers as $attackerId => $val) {
            $unit = $b->force->units[$attackerId];
            if (($unit->class == "artillery" || $unit->class == "helicopter") && $unit->status == STATUS_CAN_ADVANCE) {
                $unit->status = STATUS_ATTACKED;
            }
        }
    }


    public function phaseChange()
    {

        /* @var $battle NorthVsSouth */
        $battle = Battle::getBattle();
        /* @var $gameRules \Wargame\GameRules */
        $gameRules = $battle->gameRules;
        $forceId = $gameRules->attackingForceId;
        $turn = $gameRules->turn;
        $force = $battle->force;
        $theUnits = $battle->force->units;

        if($gameRules->phase === RED_DEPLOY_PHASE && $gameRules->turn === 1){
            $gameRules->flashMessages[] = "@show deployWrapper";
        }

        if($gameRules->mode === REPLACING_MODE){
            $gameRules->flashMessages[] = "@show deadpile";
            $gameRules->flashMessages[] = "@hide deployWrapper";
        }else{
            $gameRules->flashMessages[] = "@hide deadpile";
        }
        if ($gameRules->phase == RED_COMBAT_PHASE || $gameRules->phase == BLUE_COMBAT_PHASE) {
            $gameRules->flashMessages[] = "@hide deployWrapper";
        } else {
            $gameRules->flashMessages[] = "@hide crt";
        }

        if ($gameRules->phase == BLUE_MOVE_PHASE || $gameRules->phase == RED_MOVE_PHASE) {
            $gameRules->flashMessages[] = "@hide deployWrapper";

            foreach ($theUnits as $id => $unit) {
                $unit->railMove(false);

                if ($unit->status == STATUS_CAN_REINFORCE && $unit->reinforceTurn <= $battle->gameRules->turn && $unit->hexagon->parent != "deployBox") {
                    $theUnits[$id]->hexagon->parent = "deployBox";
                }
            }
        }
    }

    public function combatResolutionMode(){
        $b = Battle::getBattle();
        $force = $b->force;
        $units = $force->units;
        foreach($b->combatRules->combats as $combatId => $combat){
            foreach($combat->attackers as $attackerId => $fake){
                $units[$attackerId]->tried = true;
            }
            foreach($combat->defenders as $defenderId => $fake){
                $units[$defenderId]->tried = true;
            }
            $b->combatRules->crt->setCombatIndex($combatId);
        }
    }

    public function unitDeployed($arg){
        $unit = $arg[0];
        /* @var $b NorthVsSouth */
        $b  = Battle::getBattle();
    }

    public function preRecoverUnits()
    {

        $b = Battle::getBattle();
        $units = $b->force->units;
        $bef = microtime(true);

//        foreach($units as $id => $unit){
//            echo "Recover unit ";
//            $unit->recover();
//        }
        $aft = microtime(true);
        $dur = $aft - $bef;
//        echo "D: $dur :D ";
    }

//    public function preRecoverUnit($arg){
//        list($unit) = $arg;
//    }


    function isExit($args)
    {
        list($unit) = $args;
        if(((int)($unit->hexagon->number / 100) === 1)){
            return true;
        }
        return false;
    }

    public function playerTurnChange($arg)
    {

            parent::playerTurnChange($arg);
        $attackingId = $arg[0];
        $battle = Battle::getBattle();
        $mapData = $battle->mapData;
        $gameRules = $battle->gameRules;
        $gameRules->replacementsAvail = 0;
        $vp = $this->victoryPoints;
        $specialHexes = $mapData->specialHexes;
        /* @var $gameRules \Wargame\GameRules */
        $gameRules = $battle->gameRules;

        if ($gameRules->phase == BLUE_MECH_PHASE || $gameRules->phase == RED_MECH_PHASE) {
            $gameRules->flashMessages[] = "@hide crt";
        }

        if($battle->scenario->name ?? "" === "four") {
            if ($attackingId === Bulge::SOVIET_FORCE) {
                if($this->checkNorthAutobahn()) {
                    $gameRules->flashMessages[] = "Release Soviet Armor Division";
                    $this->releaseArmorDivision();
                }
            }
        }

        if($battle->scenario->name ?? "" === "three") {
            if ($attackingId === Bulge::NATO_FORCE) {
                if ($gameRules->turn >= 9)
                    $gameRules->replacementsAvail = 6;
            }
        }
    }

    public function releaseArmorDivision(){
        if($this->armorReleased){
            return;
        }
        $battle = Battle::getBattle();

        UnitFactory::$injector = $battle->force;
        $this->armorReleased = true;
        $battle = Battle::getBattle();
        $units = $battle->force->units;
        $id = count($units);
        $nextTurn = $battle->gameRules->turn + 1;
        for ($i = 0; $i < 3; $i++) {
            UnitFactory::create("|||", Bulge::SOVIET_FORCE, "gameTurn$nextTurn", "Armor.svg",
                4, 2, 12, STATUS_CAN_REINFORCE, "A", $nextTurn,
                "southern", "mech", $id++);
        }
        for ($i = 0; $i < 3; $i++) {
            UnitFactory::create("||", Bulge::SOVIET_FORCE, "gameTurn$nextTurn", "MechInf.svg",
                1, 2, 12, STATUS_CAN_REINFORCE, "A", $nextTurn,
                "southern", "mech", $id++);
        }
        UnitFactory::create("||", Bulge::SOVIET_FORCE, "gameTurn$nextTurn", "Artillery.svg",
            4, 1, 9, STATUS_CAN_REINFORCE, "A", $nextTurn,
            "southern", "artillery", $id++, 8, 0);
        UnitFactory::create("||", Bulge::SOVIET_FORCE, "gameTurn$nextTurn", "Artillery.svg",
            5, 1, 9, STATUS_CAN_REINFORCE, "A", $nextTurn,
            "southern", "artillery", $id++, 7, 1);
    }
    public function  checkNorthAutobahn(){
        if($this->armorReleased){
            return false;
        }
        $northCount = 0;
        $battle = Battle::getBattle();
        $theAutobahn = [0, 116, 216, 317, 417, 518, 617, 717, 817, 918, 1017, 1117, 1217, 1317, 1417, 1518, 1617, 1717, 1816, 1916, 2015, 2115, 2215, 2315, 2414, 2515, 2615, 2715, 2815, 2915];
        $units = $battle->force->units;
        foreach($units as $id => $unit){
            if($unit->isOnMap()){
                if($unit->forceId === Bulge::NATO_FORCE){
                    $num = $unit->hexagon->number;
                    $col = (int)($num / 100);
                    $row = $num % 100;
                    if($num < $theAutobahn[$col]){
                        $northCount++;
                    }
                }
            }
        }
        return $northCount >= 5;
    }
}