<?php
namespace Wargame\Troops\ModernTactics;
use Wargame\Troops\ModernTactics\UnitFactory;

use stdClass;

use Wargame\Battle;
use Wargame\MapData;
use Wargame\Display;
use Wargame\Force;
use Wargame\MapViewer;
use Wargame\Hexagon;
use Wargame\ModernTacticalCombatRules;
use Wargame\Terrain;
use Wargame\MoveRules;
use Wargame\GameRules;
use Wargame\Victory;
/**
 *
 * Copyright 2012-2015 David Rodal
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


global $force_name;
$force_name[2] = "PlayerTwo";


$force_name[1] = "PlayerOne";

class ModernTactics extends \Wargame\Troops\TroopersCore
{

    const FORCE_ONE = 1;
    const FORCE_TWO = 2;
    public $specialHexesMap = ['SpecialHexA' => 2, 'SpecialHexB' => 1, 'SpecialHexC' => 1];

    /* @var Mapdata */
    public $mapData;
    public $mapViewer;
    /* @var Force */
    public $force;
    /* @var Terrain */
    public $terrain;
    /* @var MoveRules */
    public $moveRules;
    public $combatRules;
    public $gameRules;
    public $victory;
    public $moodkee;


    public $players;

    static function getHeader($name, $playerData, $arg = false)
    {

        @include_once "globalHeader.php";
        @include_once "header.php";
        @include_once "ModernTacticsHeader.php";

    }


    static function enterMulti()
    {
        @include_once "enterMulti.php";
    }

    public static function buildUnit($data = false){
        return UnitFactory::build($data);
    }

    static function getPlayerData($scenario){
        $forceName = ["Observer", $scenario->playerOne, $scenario->playerTwo];
        return \Wargame\Battle::register($forceName,
            [$forceName[0], $forceName[2], $forceName[1]]);
    }

    public function terrainInit($terrainDoc)
    {
        parent::terrainInit($terrainDoc);
    }

    function save()
    {
        $data = parent::save();
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
        return $data;
    }


    public function init()
    {

        UnitFactory::$injector = $this->force;

        /* German */
        $scenario = $this->scenario;
        if(!empty($scenario->one)){

            /* German */
            for ($i = 0; $i < 6; $i++) {
                UnitFactory::create(2, "deployBox", 10, 10, 10, 6, ModernTacticalUnit::AP_WEAPONS, ModernTacticalUnit::HARD_TARGET, STATUS_CAN_DEPLOY, "B", 1,  "German", 'armor');
            }
            for ($i = 0; $i < 6; $i++) {
                UnitFactory::create(2, "deployBox", 6, 3, 5, 1, ModernTacticalUnit::SM_WEAPONS, ModernTacticalUnit::SOFT_TARGET, STATUS_CAN_DEPLOY, "B", 1,  "German", 'infantry');
            }

            /* Yanks */

            for ($i = 0; $i < 6; $i++) {
                UnitFactory::create(1, "deployBox", 7, 10, 7, 5, ModernTacticalUnit::AP_WEAPONS, ModernTacticalUnit::HARD_TARGET, STATUS_CAN_DEPLOY, "A", 1,  "American", 'armor');
            }

            for ($i = 0; $i < 3; $i++) {
                UnitFactory::create(1, "deployBox", 9, 10, 7, 5, ModernTacticalUnit::AP_WEAPONS, ModernTacticalUnit::HARD_TARGET, STATUS_CAN_DEPLOY, "A", 1,  "American", 'armor');
            }


            for ($i = 0; $i < 3; $i++) {
                UnitFactory::create(1, "deployBox", 5, 5, 5, 7, ModernTacticalUnit::AP_WEAPONS, ModernTacticalUnit::HARD_TARGET, STATUS_CAN_DEPLOY, "A", 1,  "American", 'armor');
            }

            for ($i = 0; $i < 9; $i++) {
                UnitFactory::create(1, "deployBox", 6, 3, 6, 1, ModernTacticalUnit::SM_WEAPONS, ModernTacticalUnit::SOFT_TARGET, STATUS_CAN_DEPLOY, "A", 1,  "American", 'infantry');
            }
            for ($i = 0; $i < 1; $i++) {
                UnitFactory::create(1, "deployBox", 5, 8, 3, 1, ModernTacticalUnit::AP_WEAPONS, ModernTacticalUnit::SOFT_TARGET, STATUS_CAN_DEPLOY, "A", 1,  "American", 'anti-tank');
            }

            for ($i = 0; $i < 1; $i++) {
                UnitFactory::create(1, "deployBox", 1, 10, 3, 6, ModernTacticalUnit::IN_WEAPONE, ModernTacticalUnit::HARD_TARGET, STATUS_CAN_DEPLOY, "A", 1,  "American", 'howitzer');
            }
        }

        if(!empty($scenario->two)){

            for ($i = 0; $i < 1; $i++) {
                UnitFactory::create(2, "deployBox", 12, 12, 13, 3, ModernTacticalUnit::AP_WEAPONS, ModernTacticalUnit::HARD_TARGET, STATUS_CAN_DEPLOY, "B", 1,  "German", 'armor', 'JVI');
            }
            /* German */
            for ($i = 0; $i < 3; $i++) {
                UnitFactory::create(2, "deployBox", 10, 10, 10, 6, ModernTacticalUnit::AP_WEAPONS, ModernTacticalUnit::HARD_TARGET, STATUS_CAN_DEPLOY, "B", 1,  "German", 'armor', 'V');
            }

            /* German */
            for ($i = 0; $i < 12; $i++) {
                UnitFactory::create(2, "deployBox", 9, 10, 7, 5, ModernTacticalUnit::AP_WEAPONS, ModernTacticalUnit::HARD_TARGET, STATUS_CAN_DEPLOY, "B", 1,  "German", 'armor', 'IV');
            }

            /* Soviets */
            for ($i = 0; $i < 1; $i++) {
                UnitFactory::create(1, "deployBox", 11, 11, 13, 4, ModernTacticalUnit::AP_WEAPONS, ModernTacticalUnit::HARD_TARGET, STATUS_CAN_DEPLOY, "A", 1,  "Soviet", 'armor','JS3');
            }
            for ($i = 0; $i < 4; $i++) {
                UnitFactory::create(1, "deployBox", 9, 9, 9, 8, ModernTacticalUnit::AP_WEAPONS, ModernTacticalUnit::HARD_TARGET, STATUS_CAN_DEPLOY, "A", 1,  "Soviet", 'armor','85');
            }
            for ($i = 0; $i < 16; $i++) {
                UnitFactory::create(1, "deployBox", 8, 7, 8, 8, ModernTacticalUnit::AP_WEAPONS, ModernTacticalUnit::HARD_TARGET, STATUS_CAN_DEPLOY, "A", 1,  "Soviet", 'armor','T-34');
            }
        }


    }


    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {
        /*
         * do not call parent constructor :(
        */
        $this->mapData = MapData::getInstance();
        if ($data) {
            $this->arg = $data->arg;
            $this->scenario = $data->scenario;
            $this->terrainName = $data->terrainName;
            $this->game = $data->game;

            $this->mapData->init($data->mapData);
            $this->mapViewer = array(new MapViewer($data->mapViewer[0]), new MapViewer($data->mapViewer[1]), new MapViewer($data->mapViewer[2]));



            $units = $data->force->units;
            unset($data->force->units);
            $this->force = new Force($data->force);
            foreach($units as $unit){
                $this->force->injectUnit(static::buildUnit($unit));
            }

            if(isset($data->terrain)){
                $this->terrain = new Terrain($data->terrain);

            }else{
                $this->terrain = new \stdClass();
            }
            $this->moveRules = new MoveRules($this->force, $this->terrain, $data->moveRules);
            $this->combatRules = new ModernTacticalCombatRules($this->force, $this->terrain, $data->combatRules);
            $this->gameRules = new GameRules($this->moveRules, $this->combatRules, $this->force,  $data->gameRules);
            $this->victory = new Victory($data);

            $this->players = $data->players;
        } else {
            $this->arg = $arg;
            $this->scenario = $scenario;
            $this->game = $game;

            $this->mapViewer = array(new MapViewer(), new MapViewer(), new MapViewer());
            $this->force = new Force();
            $this->terrain = new Terrain();

            $this->moveRules = new MoveRules($this->force, $this->terrain);
            $this->combatRules = new ModernTacticalCombatRules($this->force, $this->terrain);
            $this->gameRules = new GameRules($this->moveRules, $this->combatRules, $this->force);
        }

        $crt = new \Wargame\Troops\ModernTactics\CombatResultsTable();
        $this->combatRules->injectCrt($crt);
        if ($data) {

        } else {

            $this->victory = new \Wargame\Victory("\\Wargame\\Troops\\ModernTactics\\modernTacticsVictoryCore");

            $this->mapData->blocksZoc->blocked = true;
            $this->mapData->blocksZoc->blocksnonroad = true;

            $this->moveRules->enterZoc = 0;
            $this->moveRules->exitZoc = 0;
            $this->moveRules->noZocZoc = false;
            $this->moveRules->zocBlocksRetreat = false;
            $this->moveRules->oneHex = false;

            $this->gameRules->gameHasCombatResolutionMode = false;
            // game data
            if(!empty($scenario->seven)){
                $this->gameRules->setMaxTurn(12);
            }elseif(!empty($scenario->one)){
                $this->gameRules->setMaxTurn(18);
            }else{
                $this->gameRules->setMaxTurn(15);
            }


                $this->gameRules->setInitialPhaseMode(BLUE_DEPLOY_PHASE, DEPLOY_MODE);
                $this->gameRules->attackingForceId = BLUE_FORCE; /* object oriented! */
                $this->gameRules->defendingForceId = RED_FORCE; /* object oriented! */
                $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */


                $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, RED_DEPLOY_PHASE, DEPLOY_MODE, RED_FORCE, BLUE_FORCE, false);
                $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_FIRST_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);

            $this->gameRules->addPhaseChange(BLUE_FIRST_COMBAT_PHASE, RED_FIRST_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_FIRST_COMBAT_PHASE, BLUE_COMBAT_RES_PHASE, COMBAT_RESOLUTION_MODE, BLUE_FORCE, RED_FORCE, false);

            $this->gameRules->addPhaseChange(BLUE_COMBAT_RES_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE,RED_FORCE , false);
            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);

            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, BLUE_FIRST_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, true);


        }
    }
}