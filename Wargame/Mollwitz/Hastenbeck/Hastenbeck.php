<?php
namespace Wargame\Mollwitz\Hastenbeck;
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



class Hastenbeck extends \Wargame\Mollwitz\JagCore
{

    const FRENCH_FORCE = 1;
    const ALLIED_FORCE = 2;

    public $specialHexesMap = ['SpecialHexA'=>1, 'SpecialHexB'=>2, 'SpecialHexC'=>0];

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


    public $players;

    static function enterMulti()
    {
        @include_once "enterMulti.php";
    }


    static function getPlayerData($scenario){
        return \Wargame\Battle::register(["Observer", "French", "Allied"],
            ["Observer", "Allied", "French"]);
    }

    function terrainInit($terrainDoc){
        parent::terrainInit($terrainDoc);
    }

    function terrainGen($mapDoc, $terrainDoc){
        parent::terrainGen($mapDoc, $terrainDoc);
        $this->terrain->addTerrainFeature("forta","forta","f",0,0,0,false);
        $this->terrain->addNatAltEntranceCost('forta','French','artillery','blocked');
        $this->terrain->addNatAltEntranceCost('forta','French','cavalry','blocked');
        $this->terrain->addNatAltEntranceCost('forta','French','infantry','blocked');
    }




    function save()
    {
        $data = parent::save();

        $data->terrainName = $this->terrainName;
        $data->specialHexA = $this->specialHexA;
        $data->specialHexB = $this->specialHexB;
        $data->specialHexC = $this->specialHexC;
        return $data;
    }


    public function init()
    {

        $artRange = 3;
        $coinFlip = floor(2 * (rand() / getrandmax()));
        UnitFactory::$injector = $this->force;


        if(!empty($this->scenario->hastenbeck2)){
            $frenchDeploy = "C";
        }else{
            $frenchDeploy = $coinFlip == 1 ? "B": "C";
        }

        if(!empty($this->scenario->redux)){
            $frenchDeploy = "B";
            for ($i = 0; $i < 4; $i++) {
                UnitFactory::create("infantry-1", self::FRENCH_FORCE, "deployBox", "FrenchInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, $frenchDeploy, 1, 1, "French", false, 'infantry');
            }
            for ($i = 0; $i < 20; $i++) {
                UnitFactory::create("infantry-1", self::FRENCH_FORCE, "deployBox", "FrenchInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, $frenchDeploy, 1, 1, "French", false, 'infantry');
            }

            for ($i = 0; $i < 4; $i++) {
                UnitFactory::create("infantry-1", self::FRENCH_FORCE, "deployBox", "FrenchCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, $frenchDeploy, 1, 1, "French", false, 'cavalry');
            }
            for ($i = 0; $i < 7; $i++) {
                UnitFactory::create("infantry-1", self::FRENCH_FORCE, "deployBox", "FrenchCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, $frenchDeploy, 1, 1, "French", false, 'cavalry');
            }
            for ($i = 0; $i < 1; $i++) {
                UnitFactory::create("infantry-1", self::FRENCH_FORCE, "deployBox", "FrenchCavBadge.png", 3, 3, 6, true, STATUS_CAN_DEPLOY, $frenchDeploy, 1, 1, "French", false, 'cavalry');
            }
            for ($i = 0; $i < 4; $i++) {
                UnitFactory::create("infantry-1", self::FRENCH_FORCE, "deployBox", "FrenchArtBadge.png", 3, 3, 2, true, STATUS_CAN_DEPLOY, $frenchDeploy, 1, $artRange, "French", false, 'artillery');
            }



            for ($i = 0; $i < 4; $i++) {
                UnitFactory::create("infantry-1", self::ALLIED_FORCE, "deployBox", "AngInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Allied", false, 'infantry');
            }
            for ($i = 0; $i < 17; $i++) {
                UnitFactory::create("infantry-1", self::ALLIED_FORCE, "deployBox", "AngInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Allied", false, 'infantry');
            }
            for ($i = 0; $i < 3; $i++) {
                UnitFactory::create("infantry-1", self::ALLIED_FORCE, "deployBox", "AngCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Allied", false, 'cavalry');
            }
            for ($i = 0; $i < 6; $i++) {
                UnitFactory::create("infantry-1", self::ALLIED_FORCE, "deployBox", "AngCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Allied", false, 'cavalry');
            }
            for ($i = 0; $i < 4; $i++) {
                UnitFactory::create("infantry-1", self::ALLIED_FORCE, "deployBox", "AngArtBadge.png", 3, 3, 2, true, STATUS_CAN_DEPLOY, "A", 1, $artRange, "Allied", false, 'artillery');
            }

        }else {


            for ($i = 0; $i < 4; $i++) {
                UnitFactory::create("infantry-1", self::FRENCH_FORCE, "deployBox", "FrenchInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, $frenchDeploy, 1, 1, "French", false, 'infantry');
            }
            for ($i = 0; $i < 25; $i++) {
                UnitFactory::create("infantry-1", self::FRENCH_FORCE, "deployBox", "FrenchInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, $frenchDeploy, 1, 1, "French", false, 'infantry');
            }
            for ($i = 0; $i < 4; $i++) {
                UnitFactory::create("infantry-1", self::FRENCH_FORCE, "deployBox", "FrenchCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, $frenchDeploy, 1, 1, "French", false, 'cavalry');
            }
            for ($i = 0; $i < 7; $i++) {
                UnitFactory::create("infantry-1", self::FRENCH_FORCE, "deployBox", "FrenchCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, $frenchDeploy, 1, 1, "French", false, 'cavalry');
            }
            for ($i = 0; $i < 1; $i++) {
                UnitFactory::create("infantry-1", self::FRENCH_FORCE, "deployBox", "FrenchCavBadge.png", 3, 3, 6, true, STATUS_CAN_DEPLOY, $frenchDeploy, 1, 1, "French", false, 'cavalry');
            }
            for ($i = 0; $i < 6; $i++) {
                UnitFactory::create("infantry-1", self::FRENCH_FORCE, "deployBox", "FrenchArtBadge.png", 3, 3, 2, true, STATUS_CAN_DEPLOY, $frenchDeploy, 1, $artRange, "French", false, 'artillery');
            }


            for ($i = 0; $i < 4; $i++) {
                UnitFactory::create("infantry-1", self::ALLIED_FORCE, "deployBox", "AngInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Allied", false, 'infantry');
            }
            for ($i = 0; $i < 17; $i++) {
                UnitFactory::create("infantry-1", self::ALLIED_FORCE, "deployBox", "AngInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Allied", false, 'infantry');
            }
            for ($i = 0; $i < 3; $i++) {
                UnitFactory::create("infantry-1", self::ALLIED_FORCE, "deployBox", "AngCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Allied", false, 'cavalry');
            }
            for ($i = 0; $i < 6; $i++) {
                UnitFactory::create("infantry-1", self::ALLIED_FORCE, "deployBox", "AngCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Allied", false, 'cavalry');
            }
            for ($i = 0; $i < 3; $i++) {
                UnitFactory::create("infantry-1", self::ALLIED_FORCE, "deployBox", "AngArtBadge.png", 3, 3, 2, true, STATUS_CAN_DEPLOY, "A", 1, $artRange, "Allied", false, 'artillery');
            }
        }
    }

    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {
        parent::__construct($data, $arg, $scenario, $game);
        if ($data) {
            $this->specialHexA = $data->specialHexA;
            $this->specialHexB = $data->specialHexB;
            $this->specialHexC = $data->specialHexC;
        } else {
            $this->victory = new \Wargame\Victory("\\Wargame\\Mollwitz\\Hastenbeck\\hastenbeckVictoryCore");

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

            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);

            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, true);

        }
    }
}