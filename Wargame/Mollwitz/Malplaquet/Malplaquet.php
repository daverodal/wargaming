<?php
namespace Wargame\Mollwitz\Malplaquet;
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

define("ANGLO_FORCE", 1);
define("FRENCH_FORCE", 2);

global $force_name;
$force_name[ANGLO_FORCE] = "Anglo Allied";
$force_name[FRENCH_FORCE] = "French";

class Malplaquet extends \Wargame\Mollwitz\JagCore
{

    public $specialHexesMap = ['SpecialHexA'=>FRENCH_FORCE, 'SpecialHexB'=>FRENCH_FORCE, 'SpecialHexC'=>FRENCH_FORCE];

    /* @var Mapdata */
    public $mapData;
    public $mapViewer;
    public $force;
    /* @var Terrain */
    public $terrain;
    public $moveRules;
    public $combatRules;
    public $gameRules;
    public $victory;
    public $malplaquet;
    public $otherCities;


    public $players;

    static function getPlayerData($scenario){
        return \Wargame\Battle::register(["Observer", "Anglo Allied", "French"],
            ["Observer", "French", "Anglo Allied" ]);
    }

    static function enterMulti()
    {
        @include_once "enterMulti.php";
    }

    public function terrainInit($terrainDoc){
        parent::terrainInit($terrainDoc);
        $this->malplaquet = $this->specialHexA;
        $this->otherCities = $this->specialHexB;
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
        $data->malplaquet = $this->malplaquet;
        $data->otherCities = $this->otherCities;

        return $data;
    }


    public function init()
    {

        UnitFactory::$injector = $this->force;

        $artRange = 3;

        for ($i = 0; $i < 16; $i++) {
            UnitFactory::create("infantry-1", FRENCH_FORCE, "deployBox", "FreInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "French", false, 'infantry');
        }
        for ($i = 0; $i < 12; $i++) {
            UnitFactory::create("infantry-1", FRENCH_FORCE, "deployBox", "FreCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "French", false, 'cavalry');
        }
        for ($i = 0; $i < 4; $i++) {
            UnitFactory::create("infantry-1", FRENCH_FORCE, "deployBox", "FreArtBadge.png", 4, 4, 2, true, STATUS_CAN_DEPLOY, "B", 1, $artRange, "French", false, 'artillery');
        }


        for ($i = 0; $i < 8; $i++) {
            UnitFactory::create("infantry-1", ANGLO_FORCE, "deployBox", "AngInfBadge.png", 8, 8, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "AngloAllied", false, 'infantry');
        }
        $nFiveThrees = 4;
        if(!empty($this->scenario->bigBritish)){
            $nFiveThrees = 7;
        }
        for ($i = 0; $i < $nFiveThrees; $i++) {
            UnitFactory::create("infantry-1", ANGLO_FORCE, "deployBox", "AngInfBadge.png", 5, 5, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "AngloAllied", false, 'infantry');
        }
        for ($i = 0; $i < 12; $i++) {
            UnitFactory::create("infantry-1", ANGLO_FORCE, "deployBox", "AngCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "AngloAllied", false, 'cavalry');
        }
         for ($i = 0; $i < 5; $i++) {
            UnitFactory::create("infantry-1", ANGLO_FORCE, "deployBox", "AngArtBadge.png", 4, 4, 2, true, STATUS_CAN_DEPLOY, "A", 1, $artRange, "AngloAllied", false, 'artillery');
        }
    }

    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {
        parent::__construct($data, $arg, $scenario, $game);
        if ($data) {
            $this->malplaquet = $data->malplaquet;
            $this->otherCities = $data->otherCities;
        } else {
            $this->victory = new \Victory("\Wargame\\Mollwitz\\Malplaquet\\malplaquetVictoryCore");

            $this->mapData->blocksZoc->blocked = true;
            $this->mapData->blocksZoc->blocksnonroad = true;

            $this->moveRules->enterZoc = "stop";
            $this->moveRules->exitZoc = "stop";
            $this->moveRules->noZocZoc = true;
            // game data

            $this->gameRules->setMaxTurn(12);
            $this->gameRules->setInitialPhaseMode(RED_DEPLOY_PHASE, DEPLOY_MODE);
            $this->gameRules->attackingForceId = RED_FORCE; /* object oriented! */
            $this->gameRules->defendingForceId = BLUE_FORCE; /* object oriented! */
            $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */


            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_DEPLOY_PHASE, DEPLOY_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);

            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);

            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, true);

        }
    }
}