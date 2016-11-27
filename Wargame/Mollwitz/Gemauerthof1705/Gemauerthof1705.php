<?php
namespace Wargame\Mollwitz\Gemauerthof1705;
use \Wargame\Mollwitz\UnitFactory;
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

define("SWEDISH_FORCE", 1);
define("RUSSIAN_FORCE", 2);

global $force_name;
$force_name[RUSSIAN_FORCE] = "Russian";
$force_name[SWEDISH_FORCE] = "Swedish";

class Gemauerthof1705 extends \Wargame\Mollwitz\JagCore
{
    public $specialHexesMap = ['SpecialHexA'=>2, 'SpecialHexB'=>1, 'SpecialHexC'=>0];


    static function enterMulti()
    {
        @include_once "enterMulti.php";
    }

    static function playMulti($name, $wargame, $arg = false)
    {
        $deployTwo = $playerOne = "Swedish";
        $deployOne = $playerTwo = "Russian";
        @include_once "playMulti.php";
    }


    static function getPlayerData($scenario){
        return \Wargame\Battle::register(["Observer", "Swedish", "Russian"],
                                        ["Observer", "Swedish" , "Russian"]);
    }

    function terrainGen($mapDoc, $terrainDoc){
        $this->terrain->addTerrainFeature("frozenswamp", "frozenswamp", "s", 2, 1, 0, true, false);

        parent::terrainGen($mapDoc, $terrainDoc);
        $this->terrain->addTerrainFeature("redoubtfront", "redoubtfront", "d", 0, 0, 0, false);
        $this->terrain->addAltTraverseCost('redoubtfront','cavalry',1);
        $this->terrain->addTerrainFeature("trail", "trail", "r", .68, 0, 0, false);
    }

    function save()
    {
        $data = new \stdClass();
        $data->mapData = $this->mapData;
        $data->mapViewer = $this->mapViewer;
        $data->moveRules = $this->moveRules->save();
        $data->force = $this->force;
        $data->gameRules = $this->gameRules->save();
        $data->combatRules = $this->combatRules->save();
        $data->players = $this->players;
        $data->victory = $this->victory->save();
        $data->terrainName = $this->terrainName;
        $data->arg = $this->arg;
        $data->scenario = $this->scenario;
        $data->game = $this->game;
        $data->specialHexA = $this->specialHexA;
        $data->specialHexB = $this->specialHexB;

        return $data;
    }

    public function init()
    {

        $scenario = $this->scenario;
        $unitSets = $scenario->units;

        UnitFactory::$injector = $this->force;

        if(isset($scenario->commandControl)){
            UnitFactory::create("", 1, "deployBox", "", 3, false, 5, false, STATUS_CAN_DEPLOY, 'A', 1, 1, "Swedish", false, "hq",false, 2);
            UnitFactory::create("", 1, "deployBox", "", 3, false, 5, false, STATUS_CAN_DEPLOY, 'A', 1, 1, "Swedish", false, "hq",false, 2);
            UnitFactory::create("", 1, "deployBox", "", 3, false, 5, false, STATUS_CAN_DEPLOY, 'A', 1, 1, "Swedish", false, "hq",false, 2);
        }
        foreach($unitSets as $unitSet) {
            if($unitSet->forceId !== SWEDISH_FORCE){
                continue;
            }
            for ($i = 0; $i < $unitSet->num; $i++) {
                $name = isset($unitSet->name) ? $unitSet->name : "infantry-1";

                $cmdRange = false;
                if(isset($unitSet->cmdRange)){
                    $cmdRange = $unitSet->cmdRange;
                }
                if(isset($unitSet->reduced)){
                    UnitFactory::create($name, $unitSet->forceId, "deployBox", "", $unitSet->combat, $unitSet->reduced, $unitSet->movement, false, STATUS_CAN_DEPLOY, $unitSet->reinforce, 1, $unitSet->range, $unitSet->nationality, false, $unitSet->class,false, $cmdRange);
                }else{
                    UnitFactory::create($name, $unitSet->forceId, "deployBox", "", $unitSet->combat, $unitSet->combat, $unitSet->movement, true, STATUS_CAN_DEPLOY, $unitSet->reinforce, 1, $unitSet->range, $unitSet->nationality, false, $unitSet->class, false, $cmdRange);
                }
            }
        }

        if(isset($scenario->commandControl)){

            UnitFactory::create("", 2, "deployBox", "", 3, false, 5, false, STATUS_CAN_DEPLOY, 'B', 1, 1, "Russian", false, "hq",false, 2);
            UnitFactory::create("", 2, "deployBox", "", 3, false, 5, false, STATUS_CAN_DEPLOY, 'B', 1, 1, "Russian", false, "hq",false, 2);
            UnitFactory::create("", 2, "deployBox", "", 3, false, 5, false, STATUS_CAN_DEPLOY, 'B', 1, 1, "Russian", false, "hq",false, 2);
        }
        foreach($unitSets as $unitSet) {
            if($unitSet->forceId !== RUSSIAN_FORCE){
                continue;
            }
            for ($i = 0; $i < $unitSet->num; $i++) {
                $name = isset($unitSet->name) ? $unitSet->name : "infantry-1";

                $cmdRange = false;
                if(isset($unitSet->cmdRange)){
                    $cmdRange = $unitSet->cmdRange;
                }
                if(isset($unitSet->reduced)){
                    UnitFactory::create($name, $unitSet->forceId, "deployBox", "", $unitSet->combat, $unitSet->reduced, $unitSet->movement, false, STATUS_CAN_DEPLOY, $unitSet->reinforce, 1, $unitSet->range, $unitSet->nationality, false, $unitSet->class,false, $cmdRange);
                }else{
                    UnitFactory::create($name, $unitSet->forceId, "deployBox", "", $unitSet->combat, $unitSet->combat, $unitSet->movement, true, STATUS_CAN_DEPLOY, $unitSet->reinforce, 1, $unitSet->range, $unitSet->nationality, false, $unitSet->class, false, $cmdRange);
                }
            }
        }
    }

    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {

        parent::__construct($data, $arg, $scenario, $game);
        if ($data) {
            $this->specialHexA = $data->specialHexA;
            $this->specialHexB = $data->specialHexB;
        } else {
            $this->victory = new \Wargame\Victory("\\Wargame\\Mollwitz\\Gemauerthof1705\\Gemauerthof1705VictoryCore");

            $this->mapData->blocksZoc->blocked = true;
            $this->mapData->blocksZoc->blocksnonroad = true;

            $this->moveRules->enterZoc = "stop";
            $this->moveRules->exitZoc = "stop";
            $this->moveRules->noZocZoc = true;
            $this->moveRules->zocBlocksRetreat = true;

            // game data

            $this->gameRules->setMaxTurn(10);
            $this->gameRules->setInitialPhaseMode(BLUE_DEPLOY_PHASE, DEPLOY_MODE);
            $this->gameRules->attackingForceId = BLUE_FORCE; /* object oriented! */
            $this->gameRules->defendingForceId = RED_FORCE; /* object oriented! */
            $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */


            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, RED_DEPLOY_PHASE, DEPLOY_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);

//            $this->gameRules->addPhaseChange(BLUE_REPLACEMENT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);

//            $this->gameRules->addPhaseChange(RED_REPLACEMENT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, true);

        }

        $this->moveRules->stacking = function($mapHex, $forceId, $unit){
            $armyGroup = false;
            if($unit->class == "hq"){
                return false;
            }
            if($unit->name === "smallartillery"){
                $nUnits = 0;
                foreach($mapHex->forces[$forceId] as $mKey => $mVal){
                    if($this->force->units[$mKey]->class == "hq"){
                        continue;
                    }
                    $nUnits++;
                }
                return $nUnits >= 2;
            }

            $nUnits = 0;
            $smallUnit = false;
            foreach($mapHex->forces[$forceId] as $mKey => $mVal){
                if($this->force->units[$mKey]->class == "hq"){
                    continue;
                }
                if($this->force->units[$mKey]->name == "smallartillery"){
                    $smallUnit = true;
                }
                $nUnits++;
            }
            if($smallUnit){
                return $nUnits >= 2;
            }
            return $nUnits >= 1;
        };

    }
}