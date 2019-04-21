<?php
namespace Wargame\Mollwitz\Holowczyn1708;
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


//global $force_name;
//$force_name[Holowczyn1708::RUSSIAN_FORCE] = "Russian";
//$force_name[Holowczyn1708::SWEDISH_FORCE] = "Swedish";

class Holowczyn1708 extends \Wargame\Mollwitz\JagCore
{

    const SWEDISH_FORCE = 1;
    const RUSSIAN_FORCE = 2;

    public $specialHexesMap = ['SpecialHexA'=>2, 'SpecialHexB'=>1, 'SpecialHexC'=>0];

    public $pontoons;

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
            ["Observer", "Russian", "Swedish" ]);
    }


    function terrainGen($mapDoc, $terrainDoc){

        parent::terrainGen($mapDoc, $terrainDoc);
        $this->terrain->addTerrainFeature("swamp", "swamp", "s", 3, 0, 1, true, false);
        $this->terrain->addAltEntranceCost('swamp', 'cavalry', 5);
    }
    
    function save()
    {
        $data = parent::save();

        $data->specialHexA = $this->specialHexA;
        $data->specialHexB = $this->specialHexB;
        $data->pontoons = $this->pontoons;

        return $data;
    }

    public function init()
    {

        $scenario = $this->scenario;
        $unitSets = $scenario->units;
        UnitFactory::$injector = $this->force;


        foreach($unitSets as $unitSet) {
            for ($i = 0; $i < $unitSet->num; $i++) {
                if(!empty($scenario->stepReduction) && isset($unitSet->reduced)){
                    UnitFactory::create("infantry-1", $unitSet->forceId, "deployBox", "", $unitSet->combat, $unitSet->reduced, $unitSet->movement, false, STATUS_CAN_DEPLOY, $unitSet->reinforce, 1, $unitSet->range, $unitSet->nationality, false, $unitSet->class);
                }else{
                    UnitFactory::create("infantry-1", $unitSet->forceId, "deployBox", "", $unitSet->combat, $unitSet->combat, $unitSet->movement, true, STATUS_CAN_DEPLOY, $unitSet->reinforce, 1, $unitSet->range, $unitSet->nationality, false, $unitSet->class);
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
            if($data->pontoons) {
                $this->pontoons = $data->pontoons;
                foreach ($this->pontoons as $pontoon) {
                    if(isset($this->terrain) && is_object($this->terrain) && is_object($this->terrain->terrainArray) && method_exists($this->terrain,'changeTerrain')) {
                        $this->terrain->changeTerrain($pontoon, HEXAGON_CENTER, "clear");
                    }
                }
            }
        } else {
            $this->victory = new \Wargame\Victory("\\Wargame\\Mollwitz\\Holowczyn1708\\Holowczyn1708VictoryCore");

            $this->mapData->blocksZoc->blocked = true;
            $this->mapData->blocksZoc->blocksnonroad = true;

            $this->moveRules->enterZoc = "stop";
            $this->moveRules->exitZoc = "stop";
            $this->moveRules->noZocZoc = true;
            $this->moveRules->zocBlocksRetreat = true;

            // game data

            $this->gameRules->setMaxTurn(8);
            $this->gameRules->setInitialPhaseMode(RED_DEPLOY_PHASE, DEPLOY_MODE);
            $this->gameRules->attackingForceId = RED_FORCE; /* object oriented! */
            $this->gameRules->defendingForceId = BLUE_FORCE; /* object oriented! */
            $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */


            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_DEPLOY_PHASE, DEPLOY_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);

            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);

            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, true);

        }
    }
}