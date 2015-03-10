<?php
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
define("BRITISH_FORCE", 1);
define("SIKH_FORCE", 2);

global $force_name;
$force_name[BRITISH_FORCE] = "British";
$force_name[SIKH_FORCE] = "Sikh";
require_once "TroopsCore.php";

class Troops extends TroopsCore
{

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
    public $display;
    public $victory;
    public $moodkee;


    public $players;

    static function getHeader($name, $playerData, $arg = false)
    {

        @include_once "globalHeader.php";
        @include_once "header.php";
        @include_once "TroopsHeader.php";

    }


    static function enterMulti()
    {
        @include_once "enterMulti.php";
    }

    static function getView($name, $mapUrl, $player = 0, $arg = false, $scenario = false, $game = false)
    {
        global $force_name;
        $youAre = $force_name[$player];
        $deployTwo = $playerOne = "British";
        $deployOne = $playerTwo = "Sikh";
        @include_once "view.php";
    }

    public function terrainInit($terrainDoc)
    {
        parent::terrainInit($terrainDoc);
        $this->moodkee = $this->specialHexB[0];
    }

    function save()
    {
        $data = new stdClass();
        $data->mapData = $this->mapData;
        $data->mapViewer = $this->mapViewer;
        $data->moveRules = $this->moveRules->save();
        $data->force = $this->force;
        $data->gameRules = $this->gameRules->save();
        $data->combatRules = $this->combatRules->save();
        $data->players = $this->players;
        $data->display = $this->display;
        $data->victory = $this->victory->save();
        $data->terrainName = $this->terrainName;
        $data->arg = $this->arg;
        $data->scenario = $this->scenario;
        $data->game = $this->game;
        $data->moodkee = $this->moodkee;
        return $data;
    }


    public function init()
    {

        /* Sikh */
        $scenario = $this->scenario;


        for ($i = 0; $i < 5; $i++) {
            $this->force->addUnit("infantry-1", SIKH_FORCE, "deployBox", "SikhInfBadge.png", 11, 11, 5, true, STATUS_CAN_DEPLOY, "A", 1, 6, "Sikh", false, 'infantry');
        }
        for ($i = 0; $i < 3; $i++) {
            $this->force->addUnit("infantry-1", SIKH_FORCE, "deployBox", "SikhCavBadge.png", 6, 6, 5, true, STATUS_CAN_DEPLOY, "A", 1, 10, "Sikh", false, 'cavalry');
        }
        for ($i = 0; $i < 2; $i++) {
            $this->force->addUnit("infantry-1", SIKH_FORCE, "deployBox", "SikhArtBadge.png", 11, 11, 4, true, STATUS_CAN_DEPLOY, "A", 1, 26, "Sikh", false, 'artillery');
        }

        /* British */

        for ($i = 0; $i < 10; $i++) {

            $this->force->addUnit("infantry-1", BRITISH_FORCE, "deployBox", "BritInfBadge.png", 11, 11, 5, true, STATUS_CAN_DEPLOY, "B", 1, 6, "British", false, 'infantry');
        }
        for ($i = 0; $i < 6; $i++) {
            $this->force->addUnit("infantry-1", BRITISH_FORCE, "deployBox", "NativeInfBadge.png", 6, 6, 5, true, STATUS_CAN_DEPLOY, "B", 1, 10, "Native", false, 'infantry');
        }
        for ($i = 0; $i < 3; $i++) {
            $this->force->addUnit("infantry-1", BRITISH_FORCE, "deployBox", "BritCavBadge.png", 11, 11, 4, true, STATUS_CAN_DEPLOY, "B", 1, 25, "British", false, 'cavalry');
        }

    }


    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {
        parent::__construct($data, $arg, $scenario, $game);
        if ($data) {
            $this->moodkee = $data->moodkee;

        } else {

            $this->victory = new Victory("Troops/Troops/troopsVictoryCore.php");

            $this->mapData->blocksZoc->blocked = true;
            $this->mapData->blocksZoc->blocksnonroad = true;

            $this->moveRules->enterZoc = "stop";
            $this->moveRules->exitZoc = "stop";
            $this->moveRules->noZocZoc = true;
            $this->moveRules->zocBlocksRetreat = true;

            $this->gameRules->gameHasCombatResolutionMode = false;
            // game data
            if ($scenario->dayTwo) {
                $this->gameRules->setMaxTurn(14);
            } else {
                $this->gameRules->setMaxTurn(12);
            }
            $this->gameRules->setInitialPhaseMode(RED_DEPLOY_PHASE, DEPLOY_MODE);
            $this->gameRules->attackingForceId = RED_FORCE; /* object oriented! */
            $this->gameRules->defendingForceId = BLUE_FORCE; /* object oriented! */
            $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */


            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_DEPLOY_PHASE, DEPLOY_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);

            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_FIRST_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_FIRST_COMBAT_PHASE, RED_FIRST_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_FIRST_COMBAT_PHASE, BLUE_COMBAT_RES_PHASE, COMBAT_RESOLUTION_MODE, BLUE_FORCE, RED_FORCE, true);

            $this->gameRules->addPhaseChange(BLUE_COMBAT_RES_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE,BLUE_FORCE , false);

            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_SECOND_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_SECOND_COMBAT_PHASE, BLUE_SECOND_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, true);
            $this->gameRules->addPhaseChange(BLUE_SECOND_COMBAT_PHASE, RED_COMBAT_RES_PHASE, COMBAT_RESOLUTION_MODE, RED_FORCE, BLUE_FORCE, false);

            $this->gameRules->addPhaseChange(RED_COMBAT_RES_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);

        }
    }
}