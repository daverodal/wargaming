<?php
namespace  Wargame\Mollwitz\Lobositz;
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


class Lobositz extends \Wargame\Mollwitz\JagCore
{

    
    const PRUSSIAN_FORCE = 1;
    const AUSTRIAN_FORCE = 2;


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
    public $austrianSpecialHexes;
    public $prussianSpecialHexes;


    public $players;

    static function getPlayerData($scenario){
        return \Wargame\Battle::register(["Observer", "Prussian", "Austrian"],
            ["Observer", "Austrian", "Prussian" ]);
    }

    static function enterMulti()
    {
        @include_once "enterMulti.php";
    }

    public function terrainInit($terrainDoc){
        parent::terrainInit($terrainDoc);
        $this->prussianSpecialHexes = $this->specialHexA;
        $this->austrianSpecialHexes = $this->specialHexB;
    }

    function save()
    {
        $data = parent::save();

        $data->austrianSpecialHexes = $this->austrianSpecialHexes;
        $data->prussianSpecialHexes = $this->prussianSpecialHexes;

        return $data;
    }


    public function init()
    {

        $artRange = 3;
        UnitFactory::$injector = $this->force;


        for ($i = 0; $i < 3; $i++) {
            UnitFactory::create("infantry-1", self::PRUSSIAN_FORCE, "deployBox", "PruInfBadge.png", 5, 5, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        }
        for ($i = 0; $i < 11; $i++) {
            UnitFactory::create("infantry-1", self::PRUSSIAN_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        }
        for ($i = 0; $i < 3; $i++) {
            UnitFactory::create("infantry-1", self::PRUSSIAN_FORCE, "deployBox", "PruCavBadge.png", 5, 5, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'cavalry');
        }
        UnitFactory::create("infantry-1", self::PRUSSIAN_FORCE, "deployBox", "PruCavBadge.png", 4, 4, 6, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'cavalry');
        for ($i = 0; $i < 4; $i++) {
            UnitFactory::create("infantry-1", self::PRUSSIAN_FORCE, "deployBox", "PruCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'cavalry');
        }
        for ($i = 0; $i < 1; $i++) {
            UnitFactory::create("infantry-1", self::PRUSSIAN_FORCE, "deployBox", "PruArtBadge.png", 3, 3, 2, true, STATUS_CAN_DEPLOY, "B", 1, $artRange, "Prussian", false, 'artillery');
        }
        for ($i = 0; $i < 3; $i++) {
            UnitFactory::create("infantry-1", self::PRUSSIAN_FORCE, "deployBox", "PruArtBadge.png", 2, 2, 2, true, STATUS_CAN_DEPLOY, "B", 1, $artRange, "Prussian", false, 'artillery');
        }


        for ($i = 0; $i < 3; $i++) {
            UnitFactory::create("infantry-1", self::AUSTRIAN_FORCE, "deployBox", "AusInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        }
        for ($i = 0; $i < 15; $i++) {
            UnitFactory::create("infantry-1", self::AUSTRIAN_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        }
        for ($i = 0; $i < 3; $i++) {
            UnitFactory::create("infantry-1", self::AUSTRIAN_FORCE, "deployBox", "AusCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        }
        UnitFactory::create("infantry-1", self::AUSTRIAN_FORCE, "deployBox", "AusCavBadge.png", 3, 3, 6, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        for ($i = 0; $i < 3; $i++) {
            UnitFactory::create("infantry-1", self::AUSTRIAN_FORCE, "deployBox", "AusCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        }
        for ($i = 0; $i < 1; $i++) {
            UnitFactory::create("infantry-1", self::AUSTRIAN_FORCE, "deployBox", "AusArtBadge.png", 4, 4, 2, true, STATUS_CAN_DEPLOY, "A", 1, $artRange, "Austrian", false, 'artillery');
        }
        for ($i = 0; $i < 3; $i++) {
            UnitFactory::create("infantry-1", self::AUSTRIAN_FORCE, "deployBox", "AusArtBadge.png", 2, 2, 2, true, STATUS_CAN_DEPLOY, "A", 1, $artRange, "Austrian", false, 'artillery');
        }

    }

    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {
        parent::__construct($data, $arg, $scenario, $game);
        if ($data) {
            $this->austrianSpecialHexes = $data->austrianSpecialHexes;
            $this->prussianSpecialHexes = $data->prussianSpecialHexes;
        } else {

            $this->victory = new \Wargame\Victory("\\Wargame\\Mollwitz\\Lobositz\\lobositzVictoryCore");

            $this->mapData->blocksZoc->blocked = true;
            $this->mapData->blocksZoc->blocksnonroad = true;
            $this->moveRules->enterZoc = "stop";
            $this->moveRules->exitZoc = "stop";
            $this->moveRules->noZocZoc = true;

            // game data
            $this->gameRules->setMaxTurn(14);
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